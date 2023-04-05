<?php

namespace App\Controllers;

use App\Models\InfluenceArea;
use App\Models\BusinessProfile;

class InfluenceAreaController extends BaseController
{
    public function indexAction()
    {
        return InfluenceArea::find()->toArray();
    }

    public function retrieveAction(int $businessProfileId)
    {
        $businessProfile = BusinessProfile::findFirstById($businessProfileId);

        if (! $businessProfile) {
            $this->setResponse([ "error" => "Business Profile not found" ], 400);
            return;
        }

        return InfluenceArea::find("businessProfileId = {$businessProfileId}");
    }

    public function createAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirstById($businessProfileId)) {
            $this->setResponse([ "error" => "Business Profile not found" ], 400);
            return;
        }

        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'influenceAreas' => 'required|array',
            'influenceAreas.*.displayId' => 'required|numeric',
            'influenceAreas.*.latitude' => 'required|numeric',
            'influenceAreas.*.longitude' => 'required|numeric',
            'influenceAreas.*.radius' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $influenceAreas = Array();
        foreach($request->influenceAreas as $area){
            $influenceArea = new InfluenceArea();
            $influenceArea->setDisplayId($area->displayId);
            $influenceArea->setBusinessProfileId($businessProfileId);
            $influenceArea->setLatitude($area->latitude);
            $influenceArea->setLongitude($area->longitude);
            $influenceArea->setRadius($area->radius);
            if ($influenceArea->save()) {
                array_push($influenceAreas,$influenceArea);
            }
        }

        if (! $influenceAreas) {
            $this->setResponse([ "error" => "Internal Server Error" ], 500);
        } else {
            $this->setResponse($influenceAreas);
        }
    }

    public function updateAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirstById($businessProfileId)) {
            $this->setResponse([ "error" => "Business Profile not found" ], 400);
            return;
        }

        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'influenceAreas' => 'required|array',
            'influenceAreas.*.id' => 'required|numeric|exists:InfluenceArea,id',
            'influenceAreas.*.displayId' => 'required|numeric',
            'influenceAreas.*.latitude' => 'required|numeric',
            'influenceAreas.*.longitude' => 'required|numeric',
            'influenceAreas.*.radius' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $influenceAreas = Array();
        foreach($request->influenceAreas as $area){
            $influenceArea = InfluenceArea::findFirst("id = {$area->id}");
            $influenceArea->setDisplayId($area->displayId);
            $influenceArea->setLatitude($area->latitude);
            $influenceArea->setLongitude($area->longitude);
            $influenceArea->setRadius($area->radius);
            if($influenceArea->save()){
                array_push($influenceAreas,$influenceArea);
            }
        }

        if (!$influenceAreas) {
            $this->setResponse([ "error" => "Internal Server Error" ], 500);
        } else {
            $this->setResponse($influenceAreas);
        }
    }

    public function deleteAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirstById($businessProfileId)) {
            $this->setResponse([ "error" => "Business Profile not found" ], 400);
            return;
        }

        $influenceAreaIds = json_decode($this->request->get('influenceAreaIds'));

        $validation = $this->validator->validate(['influenceAreaIds' => $influenceAreaIds], [
            'influenceAreaIds' => 'required|array',
            'influenceAreaIds.*' => 'required|numeric|exists:InfluenceArea,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $influenceAreas = InfluenceArea::query()
            ->inWhere("id", $influenceAreaIds)
            ->execute();

        if (!$influenceAreas) {
            $this->setResponse([ "error" => "Internal Server Error" ], 500);
        } else {
            foreach($influenceAreas as $influenceArea)
            {
                $influenceArea->delete();
            }
            $this->setResponse($influenceAreas);
        }
    }

}
