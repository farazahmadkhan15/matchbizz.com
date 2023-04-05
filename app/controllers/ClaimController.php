<?php

namespace App\Controllers;

use App\Models\Claim;
use App\Models\BusinessProfile;
use App\Models\User;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ForbiddenException;
use App\Exceptions\ConflictException;

class ClaimController extends BaseController
{
    public function indexAction()
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  all claim"
            );
        }

        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");
        $query = $this->request->get("query");

        $queryBuilder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Claim.id",
                "Claim.status",
                "Claim.businessProfileId",
                "businessProfileName" => "businessProfile.name",
                "Claim.userId",
                "userEmail"=>"User.email"
            ])
            ->from(["Claim" => "App\Models\Claim"])
            ->join("App\Models\User", "User.id = Claim.userId", "User")
            ->join("App\Models\BusinessProfile", "businessProfile.id = Claim.businessProfileId", "businessProfile")
            ->where("Claim.deletedAt IS NULL");

        if ($query) {
            $queryBuilder->where("businessProfile.name LIKE :name:", ["name" => "%{$query}%"])
                ->orWhere("User.email LIKE :email:", ["email" => "%{$query}%"])
                ->orWhere("Claim.status LIKE :status:", ["status" => "%{$query}%"]);
        }

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $queryBuilder,
                "limit"   => $limit,
                "page"    => $currentPage
            ]
        );

        return $paginator->getPaginate();
    }

    public function claimBusinessAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array)$request, [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $claimPending = Claim::find("userId = {$this->auth->getUserId()} AND status <> 'rejected'");

        if($claimPending->count() > 0){
            throw new ConflictException("claim/user-has-a-pending-claim", "The user has a pending or approved claim");
        }

        $claim = new Claim();
        $claim->setBusinessProfileId($request->businessProfileId);
        $claim->setUserId($this->auth->getUserId());
        $claim->setStatus('pending');

        if (! $claim->save()) {
            $this->setResponse([ "error" => $this->headerCode[400] ], 400);
        } else {
            $this->setResponse($claim->toArray());
        }
    }

    public function approveAction(int $claimId)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can approve a claim"
            );
        }

        $validation = $this->validator->validate(["claimId" => $claimId], [
            'claimId' => 'required|numeric|exists:Claim,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $claim = Claim::findFirst([
            "id = :id:",
            "bind" => [
                "id" => $claimId
            ],
        ]);

        $claim->setStatus('approved');

        $businessProfile = BusinessProfile::findFirst("id = {$claim->getBusinessProfileId()}");

        $businessProfile->setUserId($claim->getUserId());

        if (! $claim->save() || ! $businessProfile->save()) {
            $this->setResponse([ "error" => $this->headerCode[400] ], 400);
        } else {
            $this->setResponse($claim->toArray());

            $claimerEmail = User::findFirstById($claim->getUserId())->getEmail();
            $message = $this->mailer->createMessage()
                ->to($claimerEmail)
                ->subject("Notification: Your Claim has been approved!")
                ->content($this->simpleView->render('email/claim-approved', [
                    'businessProfileName' => $businessProfile->getName()
                ]));
            $message->send();
        }
    }

    public function rejectAction(int $claimId)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can reject a claim"
            );
        }
        
        $validation = $this->validator->validate(["claimId" => $claimId], [
            'claimId' => 'required|numeric|exists:Claim,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $claim = Claim::findFirst([
            "id = :id:",
            "bind" => [ "id" => $claimId ]
        ]);

        if (! $claim) {
            $this->setResponse([ "error" => "Claim Not Found" ], 404);
            return;
        }

        if ($claim->getStatus() != 'pending') {
            $this->setResponse([ "error" => "Claim must be pending" ], 404);
            return;
        }

        $claim->setStatus('rejected');

        if (! $claim->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($claim->toArray());

            $claimerEmail = User::findFirstById($claim->getUserId())->getEmail();
            $businessProfileName = BusinessProfile::findFirst("id = {$claim->getBusinessProfileId()}")->getName();
            $message = $this->mailer->createMessage()
                ->to($claimerEmail)
                ->subject("Notification: Your Claim has been rejected!")
                ->content($this->simpleView->render('email/claim-rejected', [
                    'businessProfileName' => $businessProfileName
                ]));
            $message->send();
        }
    }

    public function deleteAction($claimId)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can delete a claim"
            );
        }

        $validation = $this->validator->validate(["claimId" => $claimId], [
            'claimId' => 'required|numeric|exists:Claim,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $claim = Claim::findFirstById($claimId);
        $claim->delete();

        return $claim->toArray();
    }
}