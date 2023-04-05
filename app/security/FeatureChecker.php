<?php

namespace App\Security;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ForbiddenException;
use App\Security\Auth;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Model\Manager;

class FeatureChecker extends Plugin
{
    protected $manager;
    protected $auth;

    public function __construct(Manager $manager, Auth $auth)
    {
        $this->manager = $manager;
        $this->auth = $auth;
    }

    public function check($businessProfileId, string $feature)
    {
        if ($this->auth->isAdmin() || $this->isAdminBusiness($businessProfileId)) {
            return;
        }

        if (! $this->businessHasFeature($businessProfileId, $feature)) {
            throw new ForbiddenException(
                "forbidden/{$feature}",
                "The business profile does not have active feature {$feature}"
            );
        }
    }

    public function businessHasFeature($businessProfileId, string $feature)
    {
        $builder = $this->manager
            ->createBuilder()
            ->columns(["count" => "COUNT(*)"])
            ->from(["PlanSubscription" => "App\Models\PlanSubscription"])
            ->join("App\Models\PlanFeature", "PlanFeature.planId = PlanSubscription.planId", "PlanFeature")
            ->join("App\Models\Feature", "Feature.id = PlanFeature.featureId", "Feature")
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = PlanSubscription.businessProfileId", "BusinessProfile")
            ->where(
                "PlanSubscription.businessProfileId = :businessProfileId: AND PlanSubscription.status = 'active' AND Feature.name = :feature:",
                [ "businessProfileId" => $businessProfileId, "feature" => $feature ]
            )
            ->getQuery()
            ->execute()
            ->getFirst();

        return intval($builder->count) > 0;
    }

    public function checkPlanActive($businessProfileId)
    {
        if ($this->auth->isAdmin() || $this->isAdminBusiness($businessProfileId)) {
            return;
        }

        $builder = $this->manager
            ->createBuilder()
            ->columns(["count" => "COUNT(*)"])
            ->from(["PlanSubscription" => "App\Models\PlanSubscription"])
            ->join("App\Models\BusinessProfile", "BusinessProfile.id = PlanSubscription.businessProfileId", "BusinessProfile")
            ->where(
                "PlanSubscription.businessProfileId = :businessProfileId: AND PlanSubscription.status = 'active'",
                [ "businessProfileId" => $businessProfileId ]
            )
            ->getQuery()
            ->execute()
            ->getFirst();

            if (intval($builder->count) <= 0) {
                throw new ForbiddenException(
                    "forbidden-plan-inactive",
                    "The business profile does not have plan active"
                );
            }
    }

    private function isAdminBusiness($businessProfileId)
    {
        $isAdminBusiness = $this->manager
            ->createBuilder()
            ->columns(["BusinessProfile.id"])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\UserRole", "UserRole.userId = BusinessProfile.userId", "UserRole")
            ->where(
                "UserRole.roleId = 1 AND BusinessProfile.id = :adminBusinessProfileId:",
                [ "adminBusinessProfileId" => $businessProfileId ]
            )
            ->getQuery()
            ->execute()
            ->getFirst();

        return $isAdminBusiness != null;
    }

    public function checkCanEditBusinessProfile($businessProfileId)
    {
        if ($this->auth->isAdmin()) {
            return;
        }

        $userId = $this->auth->getUserId();
        $match = $this->manager
            ->createBuilder()
            ->columns(["count" => "COUNT(*)"])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->where("BusinessProfile.userId = :userId:", [
                "userId" => $userId
            ])
            ->andWhere("BusinessProfile.id = :businessProfileId:", ["businessProfileId" => $businessProfileId])
            ->andWhere("BusinessProfile.deletedAt IS NULL")
            ->getQuery()
            ->execute()
            ->getFirst();

        if ($match->count == 0) {
            throw new ForbiddenException(
                "business-profile/edit",
                "Only 'owner' of the business or admin can edit it"
            );
        }
    }

    public function checkCanEditCustomerProfile($customerProfile)
    {
        if ($this->auth->isAdmin()) {
            return;
        }

        $userId = $this->auth->getUserId();
        $match = $this->manager
            ->createBuilder()
            ->columns(["count" => "COUNT(*)"])
            ->from(["CustomerProfile" => "App\Models\CustomerProfile"])
            ->where("CustomerProfile.userId = :userId:", [
                "userId" => $userId
            ])
            ->andWhere("CustomerProfile.id = :customerProfile:", ["customerProfile" => $customerProfile])
            ->andWhere("CustomerProfile.deletedAt IS NULL")
            ->getQuery()
            ->execute()
            ->getFirst();

        if ($match->count == 0) {
            throw ForbiddenException(
                "customer-profile/edit",
                "Only customer profile's owner or admin can edit this profile"
            );
        }
    }
}