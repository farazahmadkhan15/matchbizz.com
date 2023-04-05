<?php

namespace App\Controllers;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model\Query;
use App\Models\BusinessProfile;
use App\Exceptions\ForbiddenException;

class PaymentController extends BaseController
{
    public function indexAction()
    {
        $request = $this->request->get();
        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'businessProfileId' => 'numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");
        $businessProfileId = $this->request->get("businessProfileId");

        if ($this->auth->isBusinessOwner())  {
            $businessProfile = BusinessProfile::findFirstById($businessProfileId);

            if(! $businessProfile){
                $this->setResponse([ "error" => "Business Profile not found" ], 400);
                return;
            }
            $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);
        } else if ($this->auth->isCustomer()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  all payments"
            );
        }

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "PlanPayment.transactionId",
                "businessProfile" => "BusinessProfile.name",
                "PlanPayment.status",
                "planId" => "Plan.id",
                "planName" => "Plan.name",
                "PlanPayment.createdAt"
             ])
            ->from(["PlanPayment" => "App\Models\PlanPayment"])
            ->join("App\Models\PlanSubscription", "PlanSubscription.id = PlanPayment.planSubscriptionId", "PlanSubscription")
            ->join("App\Models\Plan", "Plan.id = PlanSubscription.planId", "Plan")
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = PlanSubscription.BusinessProfileId", "BusinessProfile")
            ->where("PlanSubscription.businessProfileId = :businessProfileId: OR :businessProfileId: IS NULL", ["businessProfileId" => $businessProfileId])
            ->andWhere("PlanSubscription.deletedAt IS NULL")
            ->andWhere("PlanPayment.deletedAt IS NULL")
            ->andWhere("Plan.deletedAt IS NULL");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }
}
