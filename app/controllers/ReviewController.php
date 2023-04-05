<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Query;
use App\Models\Review;
use App\Models\BusinessProfile;
use App\Models\CustomerProfile;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use \Phalcon\Ext\Mailer\Manager;
use App\Exceptions\ConflictException;

class ReviewController extends BaseController
{
    public function indexAction()
    {
        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $businessProfileId = $this->request->get("businessProfileId");
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");

        $this->featureChecker->check($businessProfileId, 'business-review');

        $queryBuilder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Review.id",
                "Review.title",
                "Review.rating",
                "Review.content",
                "Review.offensive",
                "Review.reply",
                "CustomerProfileId" => "CustomerProfile.id",
                "CustomerProfile.firstName",
                "CustomerProfile.lastName",
                "customerProfilethumbnail" => "Image.addressThumbnail"
            ])
            ->from(["Review" => "App\Models\Review"])
            ->join("App\Models\CustomerProfile", "CustomerProfile.id = Review.customerProfileId", "CustomerProfile")
            ->join("App\Models\Image","CustomerProfile.imageId = Image.name", "Image", "LEFT")
            ->where("Review.businessProfileId = :id:", ["id" => $businessProfileId])
            ->orderBy("Review.createdAt DESC");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $queryBuilder,
                "limit"   => $limit,
                "page"    => $currentPage
            ]
        );
    
        return $paginator->getPaginate();
    }

    public function retrieveAction(int $id){
        $review = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Review.title",
                "Review.rating",
                "Review.content",
                "Review.offensive",
                "Review.reply",
                "CustomerProfileId" => "CustomerProfile.id",
                "CustomerProfile.firstName",
                "CustomerProfile.lastName",
                "Review.businessProfileId",
            ])
            ->from(["Review" => "App\Models\Review"])
            ->join("App\Models\CustomerProfile", "CustomerProfile.id = Review.customerProfileId", "CustomerProfile")
            ->where("Review.id = :id:", ["id" => $id])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $review) {
            $this->setResponse([ "error" => "Review not found" ], 404);
            return;
        }

        $this->featureChecker->check($review->businessProfileId,'business-review');

        return $review;
    }

    public function createAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'rating' => 'required|numeric',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $this->featureChecker->check($request->businessProfileId,'business-review');

        $customerProfile = CustomerProfile::findFirst("userId = {$this->auth->getUserId()}");
        if(!$customerProfile){
            throw new ConflictException("review/customer-not-found", "The customer profile not found");
        }
        $review = new Review();
        $review->setTitle($request->title);
        $review->setRating($request->rating);
        $review->setContent($request->content);
        $review->setCustomerProfileId($customerProfile->getId());
        $review->setBusinessProfileId($request->businessProfileId);

        if (! $review->save()) {
            $this->setResponse([ "error" => "400 Bad request" ], 400);
        }else{
            $businessProfile = BusinessProfile::findFirst("id={$request->businessProfileId}");
            $newReviewCount = $businessProfile->getReviewCount() + 1;
            $rating = $businessProfile->getRating() * $businessProfile->getReviewCount();
            $newRating = ($rating + $request->rating)/$newReviewCount;
            $businessProfile->setReviewCount($newReviewCount);
            $businessProfile->setRating($newRating);
            $businessProfile->save();
            $this->setResponse($review->toArray());
        }
    }

    public function deleteAction(int $id){
        $review = Review::findFirstById($id);

        if (! $review) {
            $this->setResponse([ "error" => "Review Not Found" ], 404);
        } else  {
            $this->featureChecker->check($review->businessProfileId,'business-review');
            $this->featureChecker->checkCanEditBusinessProfile($review->businessProfileId);
            $businessProfile = BusinessProfile::findFirstById($review->businessProfileId);
            $review->delete();
            $newReviewCount = $businessProfile->getReviewCount() - 1;
            $rating = $businessProfile->getRating() * $businessProfile->getReviewCount();
            $newRating = ($rating - $review->getRating())/$newReviewCount;
            $businessProfile->setReviewCount($newReviewCount);
            $businessProfile->setRating($newRating);
            $businessProfile->save();
            $this->setResponse([ "ok" => true ]);
        }
    }

    public function toggleIsOffensiveAction(){
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array)$request, [
            'id' => 'required|numeric|exists:Review,id',
            'offensive' => 'required|in:true,false',
        ]);

        $review = Review::findFirstById($request->id);

        if (! $review) {
            $this->setResponse([ "error" => "Review Not Found" ], 404);
            return;
        }

        $this->featureChecker->check($review->businessProfileId,'business-review');
        $this->featureChecker->checkCanEditBusinessProfile($review->businessProfileId);

        $review->setOffensive(($request->offensive ? 1 : 0));
        if ($review->save()) {
            $this->setResponse([ "ok" => true ]);
        } else {
            $this->setResponse([ "error" => "Internal server error" ], 500);
        }
    }

    public function replyAction(){
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array)$request, [
            'id' => 'required|numeric|exists:Review,id',
            'reply' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $review = Review::findFirstById($request->id);

        $this->featureChecker->check($review->businessProfileId,'business-review');
        $this->featureChecker->checkCanEditBusinessProfile($review->businessProfileId);

        $customerProfile = CustomerProfile::findFirstById($review->getCustomerProfileId());
        if (! $customerProfile) {
            $this->setResponse([ "error" => "CustomerProfile Not Found" ], 404);
            return;
        }

        $review->setReply($request->reply);

        if (! $review->save()) {
            $this->setResponse([ "error" => "Unable to save the review" ], 500);
            return;
        }

        $businessProfile = BusinessProfile::findFirstById($review->getBusinessProfileId());
        $message = $this->mailer->createMessage()
            ->to($customerProfile->getEmail())
            ->subject("{$businessProfile->getName()} replied to your review")
            ->content($this->simpleView->render('email/review-reply', [
                'reply' => $request->reply
            ]));

        if ($message->send() && $review->save()) {
            $this->setResponse([ "ok" => true ]);
        } else {
            $this->setResponse([ "error" => "Internal server error" ], 500);
        }
    }

    public function getByCustomerIdAction()
    {
        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'customerProfileId' => 'required|numeric|exists:CustomerProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $customerProfileId = $this->request->get("customerProfileId");
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");

        $this->featureChecker->checkCanEditCustomerProfile($customerProfileId);

        $queryBuilder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Review.id",
                "Review.title",
                "Review.rating",
                "Review.content",
                "Review.offensive",
                "Review.reply",
                "businessProfileId" => "BusinessProfile.id",
                "BusinessProfile.name",
                "businessProfileThumbnail" => "Image.addressThumbnail"
            ])
            ->from(["Review" => "App\Models\Review"])
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = Review.businessProfileId", "BusinessProfile")
            ->join("App\Models\Image","BusinessProfile.imageId = Image.name", "Image", "LEFT")
            ->where("Review.customerProfileId = :id:", ["id" => $customerProfileId])
            ->andWhere("Review.deletedAt IS NULL")
            ->orderBy("Review.createdAt DESC");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $queryBuilder,
                "limit"   => $limit,
                "page"    => $currentPage
            ]
        );
    
        return $paginator->getPaginate();
    }
}