<?php

namespace App\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\CustomerProfile;
use App\Models\BusinessProfile;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ConflictException;

define('CUSTOMER_PROFILE_ID', 2);
define('BUSINESS_PROFILE_ID', 3);

class ConversationController extends BaseController
{
    public function indexAction()
    {
        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'profileType' => 'required|in:business,customer',
            'profileId' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $profileTable = ucfirst($this->request->get('profileType'))."Profile";
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");

        $userId = $this->auth->getUserId();

        if($this->auth->isBusinessOwner()) {
            $businessProfile = BusinessProfile::findFirst("userId = {$userId}");
            if ($businessProfile) {
                $this->featureChecker->check($businessProfile->getId(),'business-private-message');
            }
        }

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "conversation.id",
                "conversation.topic",
                "conversation.businessProfileId",
                "businessProfileName" => "BusinessProfile.name",
                "conversation.customerProfileId",
                "CustomerProfile.firstName", //"customerProfileName" => "CONCAT('CustomerProfile.firstName, CustomerProfile.lastName)",
                "CustomerProfile.lastName",
                "lastMessageDate"=> "MAX(message.createdAt)",
                "conversation.createdAt",
                "messageCount" => "COUNT(message.id)",
                "customerThumbnail" => "CustomerImage.addressThumbnail",
                "businessThumbnail" => "BusinessImage.addressThumbnail"
            ])
            ->from(["conversation" => "App\Models\Conversation"])
            ->join("App\Models\Message", "message.conversationId = conversation.id", "message")
            ->join("App\Models\CustomerProfile", "CustomerProfile.id = conversation.customerProfileId AND CustomerProfile.deletedAt IS NULL", "CustomerProfile")
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = conversation.businessProfileId AND BusinessProfile.deletedAt IS NULL", "BusinessProfile")
            ->join("App\Models\PlanSubscription", "BusinessProfile.id = planSubscription.businessProfileId", "planSubscription")  
            ->join("App\Models\Image","CustomerProfile.imageId = CustomerImage.name", "CustomerImage", "LEFT")
            ->join("App\Models\Image","BusinessProfile.imageId = BusinessImage.name", "BusinessImage", "LEFT")
            ->where("{$profileTable}.id = :profileId:", [ "profileId" => $this->request->get('profileId') ])
            ->andwhere("planSubscription.status = 'active' AND planSubscription.deletedAt IS NULL")
            ->andWhere("conversation.deletedAt IS NULL")
            ->groupBy([
                "conversation.id",
                "conversation.businessProfileId",
                "conversation.customerProfileId",
                "conversation.createdAt"
            ]);

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page" => $currentPage,
            ]
        );

        return $paginator->getPaginate();
    }

    public function messagesAction(int $conversationId)
    {
        if (! Conversation::findById($conversationId)) {
            $this->setResponse([ "error" => "Conversation Not Found" ], 404);
            return;
        }

        $request = $this->request->get();
        $validation = $this->validator->validate((Array)$request, [
            'limit' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        return $this->getMessages($conversationId, null, true, $this->request->get("limit"));
    }

    public function retrieveAction(int $conversationId)
    {
        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'limit' => 'required|numeric'
        ]);

        $conversation = $this->modelsManager
            ->createBuilder()
            ->columns([
                "conversation.id",
                "conversation.topic",
                "conversation.businessProfileId",
                "businessProfileName" => "BusinessProfile.name",
                "conversation.customerProfileId",
                "CustomerProfile.firstName",
                "CustomerProfile.lastName",
                "lastMessageDate" => "MAX(message.createdAt)",
                "firstMessageId" => "MIN(message.id)",
                "conversation.createdAt",
                "customerThumbnail" =>  "CustomerImage.addressThumbnail",
                "businessThumbnail" =>  "BusinessImage.addressThumbnail"
            ])
            ->from(["conversation" => "App\Models\Conversation"])
            ->join("App\Models\Message", "message.conversationId = conversation.id", "message")
            ->join("App\Models\CustomerProfile", "CustomerProfile.id = conversation.customerProfileId", "CustomerProfile","LEFT")
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = conversation.businessProfileId", "BusinessProfile","LEFT")
            ->join("App\Models\Image","CustomerProfile.imageId = CustomerImage.name", "CustomerImage", "LEFT")
            ->join("App\Models\Image","BusinessProfile.imageId = BusinessImage.name", "BusinessImage", "LEFT")
            ->where("conversation.id = :id:", ["id" => $conversationId])
            ->groupBy([
                "conversation.id",
                "conversation.topic",
                "conversation.businessProfileId",
                "conversation.customerProfileId",
                "conversation.createdAt"
            ])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $conversation) {
            $this->setResponse([ "error" => "Conversation Not Found" ], 404);
            return;
        }

        $conversation->messages = $this->getMessages($conversationId, null, true, $this->request->get("limit"));

        return $conversation->toArray();
    }

    public function createAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array)$request, [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
            'topic' => 'required',
            'content' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        
        $customerProfile = CustomerProfile::findFirst("userId = {$this->auth->getUserId()}");
        if(!$customerProfile){
            throw new ConflictException("conversation/customer-not-found", "The customer profile not found");
        }
        $conversation = new Conversation();
        $conversation->setBusinessProfileId($request->businessProfileId);
        $conversation->setCustomerProfileId($customerProfile->getId());
        $conversation->setTopic($request->topic);

        if (! $conversation->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $message = new Message();
            $message->setConversationId($conversation->id);
            $message->setContent($request->content);
            $message->setFrom("customer");
            $message->save();
            $conversation->message = $message;
            $this->setResponse($conversation->toArray());
        }
    }

    public function sendAction(int $conversationId)
    {
        if (! Conversation::findById($conversationId)) {
            $this->setResponse([ "error" => "Conversation Not Found" ], 404);
            return;
        }

        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array)$request, [
            'content' => 'required'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $message = new Message();

        $message->setConversationId($conversationId);
        $message->setContent($request->content);

        if ($this->auth->getRoleId() == CUSTOMER_PROFILE_ID) {
            $message->setFrom("customer");
        } else {
            $message->setFrom("business");
        }

        if (! $message->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($message->toArray());
        }
    }

    public function beforeAction(int $conversationId, int $messageId)
    {
        if (! Conversation::findById($conversationId)) {
            $this->setResponse([ "error" => "Conversation Not Found" ], 404);
            return;
        }

        $request = $this->request->get();
        $validation = $this->validator->validate((Array)$request, [
            'limit' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        return $this->getMessages($conversationId, $messageId, true, $this->request->get("limit"));
    }

    public function afterAction(int $conversationId, int $messageId)
    {
        if (! Conversation::findById($conversationId)) {
            $this->setResponse([ "error" => "Conversation Not Found" ], 404);
            return;
        }

        return $this->getMessages($conversationId, $messageId, false);
    }

    private function getMessages(int $conversationId, int $messageId = null, bool $before = true, int $limit = null)
    {
        $builder = $this->modelsManager
            ->createBuilder()
            ->columns(["Message.*"])
            ->from(["Message" => "App\Models\Message"])
            ->where("Message.conversationId = :conversationId:", ["conversationId" => $conversationId]);

        if (! is_null($messageId)) {
            $builder->andWhere("Message.id ".($before ? "<" : ">").":messageId:", ["messageId" => $messageId]);
        }

        $builder->orderBy("Message.createdAt DESC");

        if (! is_null($limit)) {
            $builder->limit($limit);
        }

        $messages = $builder->getQuery()->execute()->toArray();

        usort($messages, function($a, $b) {
            return $a['createdAt'] > $b['createdAt'];
        });

        return $messages;
    }

}
