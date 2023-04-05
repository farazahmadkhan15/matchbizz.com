<?php

namespace App\Controllers;

use App\Models\Bookmark;
use App\Models\CustomerProfile;


class BookmarkController extends BaseController
{
    public function addToFavoritesAction(int $businessProfileId)
    {
        $validation = $this->validator->validate(["businessProfileId" => $businessProfileId], [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $customerProfile = CustomerProfile::findFirst("userId = {$this->auth->getUserId()}");

        $bookmark = new Bookmark();
        $bookmark->setBusinessProfileId($businessProfileId);
        $bookmark->setCustomerProfileId($customerProfile->getId());

        if (! $bookmark->save()) {
            $bookmark = Bookmark::findFirst([
                "businessProfileId = :businessProfileId: AND customerProfileId = :customerProfileId:",
                "bind" => [
                    "businessProfileId" => $businessProfileId,
                    "customerProfileId" =>  $customerProfile->getId(),
                ],
            ]);
            $bookmark->setDeletedAt(null);
            $bookmark->save();
        }

        return [ "ok" => true ];
    }

    public function removeFromFavoritesAction(int $businessProfileId)
    {
        $validation = $this->validator->validate(["businessProfileId" => $businessProfileId], [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        
        $customerProfile = CustomerProfile::findFirst("userId = {$this->auth->getUserId()}");

        $bookmark = Bookmark::findFirst([
            "businessProfileId = :businessProfileId: AND customerProfileId = :customerProfileId:",
            "bind" => [
                "businessProfileId" => $businessProfileId,
                "customerProfileId" =>  $customerProfile->getId(),
            ],
        ]);

        if ($bookmark) {
            $bookmark->setDeletedAt(date('Y-m-d H:i:s'));
            $bookmark->save();
            $this->setResponse([ "ok" => true ]);
        } else {
            $this->setResponse([ "error" => "Bussines Not Found" ], 404);
        }
    }
}