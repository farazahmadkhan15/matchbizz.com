<?php

namespace App\Controllers;

use Phalcon\Mvc\Model\Query;
use App\Models\BusinessProfile;
use App\Models\Claim;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ForbiddenException;

class BusinessProfileController extends BaseController
{
    public function indexAction()
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  all business profiles"
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

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "BusinessProfile.id",
                "BusinessProfile.address",
                "BusinessProfile.latitude",
                "BusinessProfile.longitude",
                "BusinessProfile.email",
                "BusinessProfile.phone",
                "BusinessProfile.description",
                "BusinessProfile.license",
                "BusinessProfile.insurance",
                "BusinessProfile.rating",                
                "BusinessProfile.name",
                "BusinessProfile.type",
                "thumbnail" => "Image.addressThumbnail"
            ])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\Image","BusinessProfile.imageId = Image.name", "Image", "LEFT");
            if($query){
                $builder->where("BusinessProfile.name LIKE :name:", ["name" => "%{$query}%"])
                ->orWhere("BusinessProfile.type LIKE :type:", ["type" => "%{$query}%"])
                ->orWhere("BusinessProfile.rating LIKE :rating:", ["rating" => "%{$query}%"]);
            }
            $builder->andWhere("BusinessProfile.deletedAt IS NULL");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }

    public function retrieveAction(int $businessProfileId)
    {
        // allow the owner of the business to see it's information
        if (! $this->auth->isOwner($businessProfileId)) {
            $this->featureChecker->check($businessProfileId, 'business-informacion');
        }

        $validation = $this->validator->validate(["businessProfileId" => $businessProfileId], [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $userId = $this->auth->getUserId();

        if (is_null($userId)) {
            throw new \Exception("No user signed in");
        }

        $businessProfile = $this->modelsManager
            ->createBuilder()
            ->columns([
                "BusinessProfile.id",
                "BusinessProfile.address",
                "BusinessProfile.latitude",
                "BusinessProfile.longitude",
                "BusinessProfile.email",
                "BusinessProfile.phone",
                "BusinessProfile.description",
                "BusinessProfile.rating",
                "BusinessProfile.license",
                "BusinessProfile.insurance",
                "BusinessProfile.name",
                "BusinessProfile.type",
                "BusinessProfile.website",
                "bookmarked" => "IF(Bookmark.CustomerProfileId IS NOT NULL, 1, 0)",
                "thumbnail" => "Image.addressThumbnail",
                "image" => "Image.address"
            ])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\CustomerProfile","CustomerProfile.userId = {$userId}", "CustomerProfile", "LEFT")
            ->join(
                "App\Models\Bookmark",
                "Bookmark.businessProfileId = BusinessProfile.id AND Bookmark.customerProfileId = CustomerProfile.id AND Bookmark.deletedAt IS NULL",
                "Bookmark",
                "LEFT"
            )
            ->join("App\Models\Image","BusinessProfile.imageId = Image.name", "Image", "LEFT")
            ->where("BusinessProfile.id = :id:", ["id" => $businessProfileId])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $businessProfile) {
            $this->setResponse([ "error" => "Business profile Not Found" ], 404);
            return;
        }

        $claim = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Claim.status",
                "createdAt" => "MAX(Claim.createdAt)"
            ])
            ->from(["Claim" => "App\Models\Claim"])
            ->where("Claim.businessProfileId = :id: AND deletedAt is NULL", ["id" => $businessProfileId])
            ->groupBy(["Claim.status", "Claim.createdAt"])
            ->getQuery()
            ->execute()
            ->getFirst();

        $businessProfile->claimed = $claim->status == 'approved';

        if ($this->featureChecker->businessHasFeature($businessProfileId, 'link-your-social-networks')) {
            $socialNetworks = $this->modelsManager
                ->createBuilder()
                ->columns(["SocialNetworkAccount.urlSegment","SocialNetwork.baseUrl","SocialNetwork.name","SocialNetwork.icon"])
                ->from(["SocialNetworkAccount" => "App\Models\SocialNetworkAccount"])
                ->join("App\Models\SocialNetwork", "SocialNetworkAccount.socialNetworkId = SocialNetwork.id", "SocialNetwork")
                ->where("SocialNetworkAccount.businessProfileId = :id:", ["id" => $businessProfileId])
                ->andWhere("SocialNetworkAccount.deletedAt IS NULL")
                ->getQuery()
                ->execute();

            $businessProfile->socialNetworkAccounts = $socialNetworks;
        } else {
            $businessProfile->socialNetworkAccounts = [];
        }

        return $businessProfile;
    }

    public function createAction()
    {
        if (! $this->auth->isAdmin() && ! $this->auth->isBusinessOwner()) {
            throw new ForbiddenException(
                "forbidden/business-owner-only",
                "Only business owners can create business profiles"
            );
        }

        $request = $this->request->getJsonRawBody();

        $validator = $this->validator;
        $validation = $validator->validate((array)$request, [
            'name' => 'required',
            'type' => 'required|in:Services,Financial,Medical,Other',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'description' => 'required',
            'license' => 'required'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $businessProfile = new BusinessProfile();
        $businessProfile->setName($request->name);
        $businessProfile->setType($request->type);
        $businessProfile->setAddress($request->address);
        $businessProfile->setLongitude($request->longitude);
        $businessProfile->setLatitude($request->latitude);
        $businessProfile->setEmail($request->email);
        $businessProfile->setPhone($request->phone);
        $businessProfile->setDescription($request->description);
        $businessProfile->setLicense($request->license);
        $businessProfile->setInsurance($request->insurance);
        $businessProfile->setUserId($this->auth->getUserId());
        $businessProfile->setWebsite($request->website);
        $businessProfile->setRating(0);
        $businessProfile->setReviewCount(0);

        if (! $businessProfile->save()) {
            $this->setResponse([ "error" => "400 Bad request" ], 400);
        } else {
            if ($this->auth->isBusinessOwner()) {
                $claim = new Claim();
                $claim->setBusinessProfileId($businessProfile->getId());
                $claim->setUserId($this->auth->getUserId());
                $claim->setStatus('pending');
                $claim->save();
            }

            $this->setResponse([
                "id" => $businessProfile->getId(),
                "longitude" => $businessProfile->getLongitude(),
                "latitude" => $businessProfile->getLatitude(),
                "email" => $businessProfile->getEmail(),
                "phone" => $businessProfile->getPhone(),
                "description" => $businessProfile->getDescription(),
                "license" => $businessProfile->getLicense(),
                "insurance" => $businessProfile->getInsurance(),
                "name" => $businessProfile->getName(),
                "type" => $businessProfile->getType(),
                "website" => $businessProfile->getWebsite()
            ]);
        }
    }

    public function updateAction(int $businessProfileId)
    {
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);
        $this->featureChecker->checkPlanActive($businessProfileId, 'business-informacion');

        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array)$request + ["businessProfileId" => $businessProfileId], [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
            'name' => 'required',
            'type' => 'required|in:Services,Financial,Medical,Other',
            'address' => 'required',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'email' => 'required|email',
            'phone' => 'required',
            'description' => 'required',
            'license' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $businessProfile = BusinessProfile::findFirstById($businessProfileId);

        $businessProfile->setName($request->name);
        $businessProfile->setType($request->type);
        $businessProfile->setAddress($request->address);
        $businessProfile->setLongitude($request->longitude);
        $businessProfile->setLatitude($request->latitude);
        $businessProfile->setEmail($request->email);
        $businessProfile->setPhone($request->phone);
        $businessProfile->setDescription($request->description);
        $businessProfile->setLicense($request->license);
        $businessProfile->setInsurance($request->insurance);
        $businessProfile->setWebsite($request->website);
        if (! $businessProfile->save()) {
            $this->setResponse([ "error" => "Couldn't update business Profile" ], 400);
        } else {
            $this->setResponse([
                "id" => $businessProfile->getId(),
                "address" => $businessProfile->getAddress(),
                "longitude" => $businessProfile->getLongitude(),
                "latitude" => $businessProfile->getLatitude(),
                "email" => $businessProfile->getEmail(),
                "phone" => $businessProfile->getPhone(),
                "description" => $businessProfile->getDescription(),
                "license" => $businessProfile->getLicense(),
                "insurance" => $businessProfile->getInsurance(),
                "name" => $businessProfile->getName(),
                "type" => $businessProfile->getType(),
                "website" => $businessProfile->getWebsite()
            ]);
        }
    }

    public function getBusinessProfileAction()
    {
        $request = $this->request->get();

        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'claimStatus' => 'required|in:pending,approved,rejected',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $claimStatus = $this->request->get("claimStatus");
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "BusinessProfile.id",
                "BusinessProfile.address",
                "BusinessProfile.latitude",
                "BusinessProfile.longitude",
                "BusinessProfile.email",
                "BusinessProfile.phone",
                "BusinessProfile.description",
                "BusinessProfile.rating",
                "BusinessProfile.license",
                "BusinessProfile.insurance",
                "BusinessProfile.name",
                "BusinessProfile.type",
                "BusinessProfile.website",
                "claimed" => "IF(Claim.status IS NOT NULL AND Claim.status = 'approved', 1, 0)",
                "bookmarked" => "IF(Bookmark.businessProfileId IS NOT NULL, 1, 0)"
            ])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\Claim", "Claim.businessProfileId = BusinessProfile.id", "Claim","LEFT")
            ->join("App\Models\Bookmark", "Bookmark.businessProfileId = BusinessProfile.id", "Bookmark","LEFT")
            ->where("Claim.status = :status:", ["status" => $claimStatus])
            ->orWhere(":status: = 'none' AND Claim.status IS NULL", ["status" => $claimStatus]);
           
        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page" => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }

    public function deleteAction(int $businessProfileId)
    {
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);
        $this->featureChecker->checkPlanActive($businessProfileId, 'business-informacion');

        $businessProfile = BusinessProfile::findFirstById($businessProfileId);

        if(! $businessProfile){
            $this->setResponse([ "error" => "Business Profile not found" ], 400);
            return;
        }

        $businessProfile->delete();

        $this->setResponse([ "ok" => true ]);
    }

    public function updateProfileImageAction(int $id)
    {
        $galleryImageFactory = $this->di->get('GalleryImageFactory');
        $image = $galleryImageFactory->createImage($this->request->getUploadedFiles()[0]);

        $businessProfile = BusinessProfile::findFirst("id = {$id}");
        $businessProfile->setImageId($image->getName());
        if (! $businessProfile->save()) {
            $this->setResponse([ "error" => "Unable to save profile image" ], 500);
        } else {
            $this->setResponse($image->toArray());
        }
    }
}