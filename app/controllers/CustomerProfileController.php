<?php

namespace App\Controllers;

use App\Models\CustomerProfile;
use App\Models\Bookmark;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ForbiddenException;

class CustomerProfileController extends BaseController
{
    public function indexAction() {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  all customer profiles"
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
                "CustomerProfile.id",
                "CustomerProfile.firstName",
                "CustomerProfile.lastName",
                "CustomerProfile.gender",
                "CustomerProfile.email",
                "CustomerProfile.phone",
                "CustomerProfile.address",
                "CustomerProfile.gender",
                "CustomerProfile.languageId",
                "CustomerProfile.imageId",
                "CustomerProfile.latitude",
                "CustomerProfile.longitude",
                "CustomerProfile.userId",
                "thumbnail" => "Image.addressThumbnail"
            ])
            ->from(["CustomerProfile" => "App\Models\CustomerProfile"])
            ->join("App\Models\Image","CustomerProfile.imageId = Image.name", "Image", "LEFT");
            
            if($query){
                $builder->where("CustomerProfile.firstName LIKE :first:", ["first" => "%{$query}%"])
                ->orWhere("CustomerProfile.lastName LIKE :last:", ["last" => "%{$query}%"])
                ->orWhere("CustomerProfile.email LIKE :email:", ["email" => "%{$query}%"]);
            }

            $builder->andWhere("CustomerProfile.deletedAt IS NULL");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }

    public function retrieveAction(int $id)
    {
        $customerProfile = $this->modelsManager
            ->createBuilder()
            ->columns([
                "CustomerProfile.id",
                "CustomerProfile.firstName",
                "CustomerProfile.lastName",
                "CustomerProfile.gender",
                "CustomerProfile.email",
                "CustomerProfile.phone",
                "CustomerProfile.address",
                "CustomerProfile.gender",
                "CustomerProfile.languageId",
                "CustomerProfile.imageId",
                "CustomerProfile.latitude",
                "CustomerProfile.longitude",
                "CustomerProfile.userId",
                "thumbnail" => "Image.addressThumbnail",
                "image" => "Image.address"
            ])
            ->from(["CustomerProfile" => "App\Models\CustomerProfile"])
            ->join("App\Models\Image","CustomerProfile.imageId = Image.name", "Image", "LEFT")
            ->where("CustomerProfile.id = :id:", ["id" => $id])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $customerProfile) {
            $this->setResponse([ "error" => "Customer profile Not Found" ], 404);
        } else {
            $this->setResponse($customerProfile->toArray());
        }
    }

    public function createAction()
    {
        if (! $this->auth->isAdmin() && ! $this->auth->isCustomer()) {
            throw new ForbiddenException(
                "forbidden/customer-only",
                "Only customers can create customer profiles"
            );
        }

        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'languageId' => 'required|exists:Language,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $customerProfile = new CustomerProfile();
        $customerProfile->setFirstName($request->firstName);
        $customerProfile->setLastName($request->lastName);
        $customerProfile->setGender($request->gender);
        $customerProfile->setEmail($request->email);
        $customerProfile->setUserId($this->di->get('auth')->getUserId());
        $customerProfile->setLanguageId($request->languageId);
        $customerProfile->setLatitude($request->latitude);
        $customerProfile->setLongitude($request->longitude);
        $customerProfile->setPhone($request->phone);
        $customerProfile->setAddress($request->address);

        if (! $customerProfile->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($customerProfile->toArray());
        }
    }

    public function updateAction(int $id)
    {
        $this->featureChecker->checkCanEditCustomerProfile($id);

        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request + ["customerProfileId" => $id], [
            'customerProfileId' => 'required|numeric|exists:CustomerProfile,id',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'languageId' => 'required|exists:Language,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $customerProfile = CustomerProfile::findFirstById($id);

        if (! $customerProfile) {
            $this->setResponse([ "error" => "Customer profile Not Found" ], 404);
            return;
        }

        $customerProfile->setFirstName($request->firstName);
        $customerProfile->setLastName($request->lastName);
        $customerProfile->setGender($request->gender);
        $customerProfile->setEmail($request->email);
        $customerProfile->setLanguageId($request->languageId);
        $customerProfile->setLatitude($request->latitude);
        $customerProfile->setLongitude($request->longitude);
        $customerProfile->setPhone($request->phone);
        $customerProfile->setAddress($request->address);

        if (! $customerProfile->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($customerProfile->toArray());
        }
    }

    public function deleteAction(int $id)
    {
        $this->featureChecker->checkCanEditCustomerProfile($id);

        $customer = CustomerProfile::findFirstById($id);

        if (! $customer) {
            $this->setResponse([ "error" => "Customer Not Found" ], 404);
        } else  {
            $customer->delete();
            $this->setResponse([ "ok" => true ]);
        }
    }

    public function bookmarkAction(int $id){
        $this->featureChecker->checkCanEditCustomerProfile($id);

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

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "BusinessProfile.id",
                "BusinessProfile.email",
                "BusinessProfile.phone",
                "BusinessProfile.name",
                "BusinessProfile.type",
                "BusinessProfile.reviewCount",
                "BusinessProfile.description",
                "BusinessProfile.rating",
                "thumbnail" => "Image.addressThumbnail"
            ])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\UserRole", "UserRole.userId = BusinessProfile.userId", "UserRole", "LEFT")
            ->join("App\Models\PlanSubscription", "BusinessProfile.id = planSubscription.businessProfileId", "planSubscription", "LEFT")
            ->join("App\Models\Bookmark", "Bookmark.businessProfileId = BusinessProfile.id", "Bookmark")
            ->join("App\Models\Image", "BusinessProfile.imageId = Image.name", "Image", "LEFT")
            ->where("Bookmark.customerProfileId = :customerProfileId:", ["customerProfileId" => $id])
            ->andWhere("planSubscription.status = 'active' OR UserRole.roleId = 1")
            ->andWhere("Bookmark.deletedAt IS NULL")
            ->andWhere("BusinessProfile.deletedAt IS NULL")
            ->andWhere("planSubscription.deletedAt IS NULL");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }

    public function retrieveByUserIdAction(int $userId)
    {
        if($userId == $this->auth->getUserId())
        {
            throw new ForbiddenException(
                "forbidden/customer-only",
                "Only user authenticated can see customer profiles"
            );
        }

        $validator = $this->di->get('RequestValidator');
        $validation = $validator->validate(["userId" => $userId], [
            'userId' => 'exists:User,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $customerProfile = CustomerProfile::findFirst("userId = {$userId}");

        if (! $customerProfile) {
            $this->setResponse([ "error" => "Customer profile Not Found" ], 404);
        } else {
            $this->setResponse($customerProfile->toArray());
        }
    }

    public function updateProfileImageAction(int $id)
    {
        $galleryImageFactory = $this->di->get('GalleryImageFactory');
        $image = $galleryImageFactory->createImage($this->request->getUploadedFiles()[0]);

        $customerProfile = CustomerProfile::findFirst("id = {$id}");
        $customerProfile->setImageId($image->getName());
        if (! $customerProfile->save()) {
            $this->setResponse([ "error" => "Unable to save profile image" ], 500);
        } else {
            $this->setResponse($image->toArray());
        }
    }
}
