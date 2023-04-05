<?php

namespace App\Controllers;

use App\Models\WorkerProfile;
use App\Models\WorkerProfileHobbie;
use App\Models\WorkerProfileLanguage;
use App\Models\BusinessProfile;
use App\Exceptions\ConflictException;

const MAXIMUN_NUMBER_WORKERS = 3;

class WorkerProfileController extends BaseController
{
    public function indexAction(int $businessProfileId)
    {
        $this->featureChecker->check($businessProfileId,'employees-information');

        $businessProfiles = $this->checkBusinessProfile($businessProfileId);

        $workerProfiles = $this->modelsManager
            ->createBuilder()
            ->columns([
                "WorkerProfile.id",
                "WorkerProfile.name",
                "WorkerProfile.age",
                "WorkerProfile.isOwner",
                "WorkerProfile.yearsOfExperience",
                "gender"  => "Gender.name",
                "ethnicity"  => "Ethnicity.name",
                "maritalStatus"  => "MaritalStatus.name",
                "education"  => "Education.name",
                "lifeStyle"  => "LifeStyle.name",
                "faith"  => "Faith.name",
            ])
            ->from(["WorkerProfile" => "App\Models\WorkerProfile"])
            ->join("App\Models\Gender", "Gender.id = WorkerProfile.genderId", "Gender")
            ->join("App\Models\Ethnicity", "Ethnicity.id = WorkerProfile.ethnicityId", "Ethnicity")
            ->join("App\Models\MaritalStatus", "MaritalStatus.id = WorkerProfile.maritalStatusId", "MaritalStatus")
            ->join("App\Models\Education", "Education.id = WorkerProfile.educationId", "Education")
            ->join("App\Models\LifeStyle", "LifeStyle.id = WorkerProfile.lifeStyleId", "LifeStyle")
            ->join("App\Models\Faith", "Faith.id = WorkerProfile.faithId", "Faith")
            ->where("WorkerProfile.businessProfileId = :businessProfileId: AND deletedAt IS NULL", [
                "businessProfileId" => $businessProfileId
            ])
            ->getQuery()
            ->execute();

        if(!$workerProfiles){ 
            return []; 
        }

        $data['hobbies'] = $this->hobbiesByBusinessProfile($businessProfileId);
        $data['languages'] = $this->languageByBusinessProfile($businessProfileId);

        return array_map(function($profile) use ($data) {
            $profile['hobbies'] = array_values(array_filter($data['hobbies'], function ($hobbie) use ($profile) {
                return $hobbie['workerProfileId'] == $profile['id'];
            }));
            $profile['hobbies'] = array_map(function($hobbie){
                return $hobbie['hobbie'];
            },  $profile['hobbies']);

            $profile['languages'] = array_values(array_filter($data['languages'], function ($language) use ($profile) {
                return $language['workerProfileId'] == $profile['id'];
            }));
            $profile['languages'] = array_map(function($language){
                return $language['language'];
            }, $profile['languages']);

            return $profile;
        }, $workerProfiles->toArray());
    }

    private function hobbiesByBusinessProfile($businessProfileId){
        $hobbies = $this->modelsManager
            ->createBuilder()
            ->columns([
                "workerProfileId" => "WorkerProfile.id",
                "hobbie" => "Hobbie.name"
            ])
            ->from(["WorkerProfile" => "App\Models\WorkerProfile"])
            ->join("App\Models\WorkerProfileHobbie", "WorkerProfileHobbie.WorkerProfileId = WorkerProfile.id", "WorkerProfileHobbie")
            ->join("App\Models\Hobbie", "Hobbie.id = WorkerProfileHobbie.hobbieId", "Hobbie")
            ->where("WorkerProfile.businessProfileId = :businessProfileId: AND WorkerProfile.deletedAt IS NULL", [
                "businessProfileId" => $businessProfileId
            ])
            ->getQuery()
            ->execute();

        return $hobbies->toArray();
    }

    private function languageByBusinessProfile($businessProfileId){
        $language = $this->modelsManager
            ->createBuilder()
            ->columns([
                "workerProfileId" => "WorkerProfile.id",
                "language" => "Language.name"
            ])
            ->from(["WorkerProfile" => "App\Models\WorkerProfile"])
            ->join("App\Models\WorkerProfileLanguage", "WorkerProfileLanguage.WorkerProfileId = WorkerProfile.id", "WorkerProfileLanguage")
            ->join("App\Models\Language", "Language.id = WorkerProfileLanguage.languageId", "Language")
            ->where("WorkerProfile.businessProfileId = :businessProfileId: AND WorkerProfile.deletedAt IS NULL", [
                "businessProfileId" => $businessProfileId
            ])
            ->getQuery()
            ->execute();

        return $language->toArray();
    }

    public function retrieveAction(int $businessProfileId, int $id)
    {
        $this->featureChecker->check($businessProfileId,'employees-information');

        $businessProfile = $this->checkBusinessProfile($businessProfileId);
        if (! $businessProfile) return;

        $workerProfile = WorkerProfile::findFirst("businessProfileId = {$businessProfileId} and id = {$id}");
        if (! $workerProfile) {
            $this->setResponse([ 'error' => 'Worker profile Not Found' ], 404);
        } else {
            return $this->formatWorkerProfile($workerProfile);
        }
    }

    public function createAction(int $businessProfileId)
    {
        $this->featureChecker->check($businessProfileId, 'employees-information');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $businessProfile = $this->checkBusinessProfile($businessProfileId);
        if(! $businessProfile) return;

        $request = $this->request->getJsonRawBody();
        $validation = $this->validateRequest($request);
        if ($validation->fails()) {
            $this->setResponse([ 'error' => $validation->errors()->toArray() ], 400);
            return;
        }

        $workerProfileCount = WorkerProfile::count("businessProfileId = {$businessProfileId} AND deletedAt IS NULL");

        if ($workerProfileCount >= MAXIMUN_NUMBER_WORKERS) {
            throw new ConflictException("worker-profile/maximun-number-reached", "The business has exceeded the maximum number of worker profiles");
        }
        $workerProfile = new WorkerProfile();
        $workerProfile->setBusinessProfileId($businessProfileId);
        $workerProfile->fromObject($request);

        if (! $workerProfile->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) workerprofile',
                'request' => $request,
                'profile' => $workerProfile->toArray(),
            ], 500);
            return;
        }

        $this->updateOrCreate($workerProfile->getId(), $request->hobbieIds, WorkerProfileHobbie::class, 'setHobbieId', 'hobbieId');
        $this->updateOrCreate($workerProfile->getId(), $request->languageIds, WorkerProfileLanguage::class, 'setLanguageId', 'languageId');
        return $this->formatWorkerProfile($workerProfile);
    }

    public function updateAction(int $businessProfileId)
    {
        $this->featureChecker->check($businessProfileId,'employees-information');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $businessProfile = $this->checkBusinessProfile($businessProfileId);
        if(! $businessProfile) return;

        $request = $this->request->getJsonRawBody();

        $workerProfile = WorkerProfile::findFirst("businessProfileId = {$businessProfileId} and id = {$request->id}");
        if (! $workerProfile) {
            $this->setResponse([ 'error' => 'Worker profile Not Found' ], 404);
            return;
        }

        $validation = $this->validateRequest($request);
        if ($validation->fails()) {
            $this->setResponse([ 'error' => $validation->errors()->toArray() ], 400);
            return;
        }

        $workerProfile->fromObject($request);

        if (! $workerProfile->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) workerprofile',
                'request' => $request,
                'profile' => $workerProfile->toArray(),
            ], 500);
            return;
        }

        $this->updateOrCreate($workerProfile->getId(), $request->hobbieIds, WorkerProfileHobbie::class, 'setHobbieId', 'hobbieId');
        $this->updateOrCreate($workerProfile->getId(), $request->languageIds, WorkerProfileLanguage::class, 'setLanguageId', 'languageId');
        return $this->formatWorkerProfile($workerProfile);
    }

    public function deleteAction(int $businessProfileId, int $id)
    {
        $this->featureChecker->check($businessProfileId,'employees-information');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $businessProfile = $this->checkBusinessProfile($businessProfileId);
        if(! $businessProfile) return;

        $workerProfile = WorkerProfile::findFirst("businessProfileId = {$businessProfileId} and id = {$id}");
        if (! $workerProfile) {
            $this->setResponse([ 'error' => 'Worker profile Not Found' ], 404);
        } else {
            $workerProfile->delete();
            return [ 'ok' => true ];
        }
    }

    public function checkBusinessProfile(int $businessProfileId) {
        $businessProfile = BusinessProfile::findFirst("id = {$businessProfileId}");
        if ($businessProfile) {
            return $businessProfile->toArray();
        }
        $this->setResponse([ 'error' => 'Busines Profile not found.'], 400);
    }

    private function updateOrCreate(int $workerProfileId, array $instanceIds, $instance, $method, $idQuery)
    {
        foreach($instanceIds as $element)
        {
            $object = new $instance();
            $object->setWorkerProfileId($workerProfileId);
            $object->$method($element);
            $object->save();
        }

        $objects = $instance::query()
            ->where("workerProfileId = {$workerProfileId}")
            ->notInWhere($idQuery, $instanceIds)
            ->execute();

        foreach($objects as $obj) {
            $obj->delete();
        }
    }

    private function validateRequest($request)
    {
        $validator = $this->di->get('RequestValidator');
        $validation = $validator->validate(json_decode(json_encode($request), true), [
            'age' => 'required|numeric',
            'yearsOfExperience' => 'required|numeric',
            'ethnicityId' => 'required|exists:Ethnicity,id',
            'genderId' => 'required|exists:Gender,id',
            'faithId' => 'required|exists:Faith,id',
            'maritalStatusId' => 'required|exists:MaritalStatus,id',
            'educationId' => 'required|exists:Education,id',
            'languageIds' => 'array',
            'languageIds.*' => 'exists:Language,id',
            'hobbieIds' => 'array',
            'hobbieIds.*' => 'exists:Hobbie,id',
        ]);
        return $validation;
    }

    public function formatWorkerProfile(WorkerProfile $workerProfile)
    {
        return $workerProfile ? $workerProfile->appends(['hobbieIds', 'languageIds'])->toArray() : null;
    }

    public function markAsOwnerAction(int $id)
    {
        $workerProfile = WorkerProfile::findFirst("id = {$id}");
        if (! $workerProfile) {
            $this->setResponse([ 'error' => 'Worker profile Not Found' ], 404);
            return;
        } 

        $businessProfile = $this->checkBusinessProfile($workerProfile->getBusinessProfileId());

        if(! $businessProfile) return;

        foreach(WorkerProfile::find("businessProfileId = {$workerProfile->getBusinessProfileId()}") as $worker){
            $worker->setIsOwner(0);
            $worker->save();
        }

        $workerProfile->setIsOwner(1);
        if (! $workerProfile->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) workerprofile',
                'profile' => $workerProfile->toArray(),
            ], 500);
            return;
        }
        return $this->formatWorkerProfile($workerProfile);
    }

    public function unmarkAsOwnerAction(int $id)
    {
        $workerProfile = WorkerProfile::findFirstById($id);
        if (! $workerProfile) {
            $this->setResponse([ 'error' => 'Worker profile Not Found' ], 404);
            return;
        }

        $businessProfileId = $this->checkBusinessProfile($workerProfile->getBusinessProfileId());

        if(! $businessProfileId) return;

        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $workerProfile->setIsOwner(0);
        if (! $workerProfile->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) workerprofile',
                'profile' => $workerProfile->toArray(),
            ], 500);
            return;
        }
        return $this->formatWorkerProfile($workerProfile);
    }

}
