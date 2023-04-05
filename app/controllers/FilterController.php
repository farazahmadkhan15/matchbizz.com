<?php

namespace App\Controllers;

use Phalcon\Mvc\Model\Query;
use App\Models\AgeRange;
use App\Models\Education;
use App\Models\Ethnicity;
use App\Models\Faith;
use App\Models\Language;
use App\Models\LifeStyle;
use App\Models\MaritalStatus;
use App\Models\BusinessProfile;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;

class FilterController extends BaseController
{
    public function retrieveValueAction(string $entityName)
    {       
        $validation = $this->validator->validate(["entity" => $entityName], [
            'entity' => 
            'required|in:education,ethnicity,faith,hobbie,language,life-style,marital-status,gender'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $entityName = ucwords($this->dashesToCamelCase($entityName));
        $entity = $this->modelsManager
            ->createBuilder()
            ->columns(["id", "name"])
            ->from(["Entity" => "App\Models\\$entityName"])
            ->getQuery()
            ->execute();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
        } else {
            $this->setResponse($entity->toArray());
        }
    }

    private function dashesToCamelCase($string) 
    {
        return str_replace('-', '', ucwords($string, '-'));
    }

    public function retrieveRangeAction(string $entityName)
    {       
        $validation = $this->validator->validate(["entity" => $entityName], [
            'entity' => 'required|in:age-range,years-of-experience-range'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $entityName = ucwords($this->dashesToCamelCase($entityName));
        $entity = $this->modelsManager
            ->createBuilder()
            ->columns(["id","min","max"])
            ->from(["Entity" => "App\Models\\$entityName"])
            ->getQuery()
            ->execute();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
        } else {
            $this->setResponse($entity->toArray());
        }
    }

    public function updateValueAction(int $id)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  update filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'entity' => 
            'required|in:education,ethnicity,faith,hobbie,language,life-style,marital-status,gender',
            'name' => 'required'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($request->entity));

        $entity = $this->modelsManager
            ->createBuilder()
            ->from(["Entity" => "App\Models\\$entityName"])
            ->where("Entity.id = :id:", ["id" => $id])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }

        $entity->setName($request->name);

        if (!$entity->save()) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }

        $this->setResponse($entity->toArray());
    }

    public function updateRangeAction(int $id)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  update filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'entity' => 
            'required|in:age-range,years-of-experience-range',
            'range' => 'required',
            'range' => 'greaterThanNullable:max,min'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($request->entity));

        $entity = $this->modelsManager
            ->createBuilder()
            ->from(["Entity" => "App\Models\\$entityName"])
            ->where("Entity.id = :id:", ["id" => $id])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }

        if (! is_null($request->range->max) && strlen(trim($request->range->max)) > 0) {
            $entity->setMax($request->range->max);
        } else {
            $entity->setMax(null);
        }
        if (! is_null($request->range->min) && strlen(trim($request->range->min)) > 0) {
            $entity->setMin($request->range->min);
        } else {
            $entity->setMin(null);
        }

        if (!$entity->save()) {
            $this->setResponse([ "error" => "Unable to update {$entityName}" ], 404);
            return;
        }

        $this->setResponse($entity->toArray());
    }

    public function createValueAction()
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  create filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'entity' => 
            'required|in:education,ethnicity,faith,hobbie,language,life-style,marital-status,gender',
            'name' => 'required'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($request->entity));
        $entityClass = "App\Models\\$entityName";

        $entity = new $entityClass();
        $entity->setName($request->name);

        if (! $entity->save()) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }

        $this->setResponse($entity->toArray());
    }

    public function createRangeAction()
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  create filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'entity' => 
            'required|in:age-range,years-of-experience-range',
            'range' => 'required|greaterThanNullable:max,min',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($request->entity));
        $entityClass = "App\Models\\$entityName";

        $entity = new $entityClass();
        if (! is_null($request->range->max) && strlen(trim($request->range->max)) > 0) {
            $entity->setMax($request->range->max);
        }
        if (! is_null($request->range->min) && strlen(trim($request->range->min)) > 0) {
            $entity->setMin($request->range->min);
        }

        if (! $entity->save()) {
            $this->setResponse([ "error" => $this->headerCode[400] ], 400);
            return;
        }

        $this->setResponse($entity->toArray());
    }

    public function deleteValueAction(string $entity, int $id)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  delete filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(["entity" => $entity], [
            'entity' => 
            'required|in:education,ethnicity,faith,hobbie,language,life-style,marital-status,gender',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($entity));

        $entity = $this->modelsManager
            ->createBuilder()
            ->from(["Entity" => "App\Models\\$entityName"])
            ->where("Entity.id = :id:", ["id" => $id])       
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }

        try {
            $entity->delete();
            $this->setResponse($entity->toArray());
        } catch (\PDOException $e) {
            if ($e->getCode() == "23000") {
                throw new ConflictException(
                    "filter/unable-to-delete",
                    "Filter is being referenced in a worker profile record"
                );
            }
        }
    }

    public function deleteRangeAction(string $entity, int $id)
    {
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can see  delete filters"
            );
        }
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(["entity" => $entity], [
            'entity' => 
            'required|in:age-range,years-of-experience-range',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $entityName = ucwords($this->dashesToCamelCase($entity));

        $entity = $this->modelsManager
            ->createBuilder()
            ->from(["Entity" => "App\Models\\$entityName"])
            ->where("Entity.id = :id:", ["id" => $id])          
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $entity) {
            $this->setResponse([ "error" => "{$entityName} Not Found" ], 404);
            return;
        }
        $entity->delete();
        $this->setResponse($entity->toArray());
    }

    public function filtersBusinessProfileAction(int $businessProfileId)
    {
        $businessProfile = BusinessProfile::findFirst("id = {$businessProfileId}");
        if(!$businessProfile) {
            $this->setResponse([ 'error' => 'Busines Profile not found.'], 400);
        }
        $filters = Array();
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'ethnicity')
        );
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'gender')
        );
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'marital-status')
        );
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'life-style')
        );
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'faith')
        );
        $filters = array_merge(
            $filters,
            $this->filterValueByBusinessProfile($businessProfileId,'education')
        );
        $filters = array_merge(
            $filters,
            $this->filterRangeByBusinessProfile($businessProfileId,'age-range')
        );
        $filters = array_merge(
            $filters,
            $this->filterRangeByBusinessProfile($businessProfileId,'years-of-experience-range')
        );
        $filters = array_merge(
            $filters,
            $this->filterMultipleByBusinessProfile($businessProfileId,'hobbie')
        );
        $filters = array_merge(
            $filters,
            $this->filterMultipleByBusinessProfile($businessProfileId,'language')
        );
        return $filters;
    }

    private function filterValueByBusinessProfile(int $businessProfileId, string $entity)
    {
        $entityName = ucwords($this->dashesToCamelCase($entity));
        $entityValues = $this->modelsManager
            ->createBuilder()
            ->columns([
                "name"=>"'{$entity}'",
                "value"=>"Entity.name"
                ])
            ->from(["Entity" => "App\Models\\$entityName"])
            ->join("App\Models\WorkerProfile", "Entity.id = WorkerProfile.".lcfirst($entityName)."Id", "WorkerProfile")
            ->where("WorkerProfile.businessProfileId = :businessProfileId:", ["businessProfileId" => $businessProfileId])
            ->groupBy("Entity.name")
            ->getQuery()
            ->execute();
        return $entityValues->toArray();
    }

    private function filterRangeByBusinessProfile(int $businessProfileId, string $entity)
    {
        $entityName = ucwords($this->dashesToCamelCase($entity));
        $entityField = substr($entityName, 0, -5);
        $entityValues = $this->modelsManager
            ->createBuilder()
            ->columns([
                "name"=>"'{$entity}'",
                "value" => 'CONCAT(COALESCE(Entity.min,"")," - ",COALESCE(Entity.max,""))'
                ])
            ->from(["Entity" => "App\Models\\$entityName"])
            ->join( "App\Models\WorkerProfile", 
                    "(WorkerProfile.{$entityField} BETWEEN Entity.min AND Entity.max
                    OR (Entity.min IS NULL AND WorkerProfile.{$entityField} <= Entity.max)
                    OR (Entity.max IS NULL AND WorkerProfile.{$entityField} >= Entity.min))",
                    "WorkerProfile")
            ->where("WorkerProfile.businessProfileId = :businessProfileId:", ["businessProfileId" => $businessProfileId])
            ->groupBy(["Entity.min","Entity.max"])
            ->getQuery()
            ->execute();
        return $entityValues->toArray();
    }

    private function filterMultipleByBusinessProfile(int $businessProfileId, string $entity)
    {
        $entityName = ucwords($this->dashesToCamelCase($entity));
        $workerEntity = "WorkerProfile".$entityName;
        $hobbies = $this->modelsManager
            ->createBuilder()
            ->columns([
                "name"=>"'{$entity}'",
                "value" => "Entity.name"
            ])
            ->from(["WorkerProfile" => "App\Models\WorkerProfile"])
            ->join("App\Models\\$workerEntity", "workerEntity.WorkerProfileId = WorkerProfile.id", "workerEntity")
            ->join( "App\Models\\$entityName", "Entity.id = workerEntity.".lcfirst($entityName)."Id", "Entity")
            ->where("WorkerProfile.businessProfileId = :businessProfileId: AND WorkerProfile.deletedAt IS NULL", [
                "businessProfileId" => $businessProfileId
            ])
            ->groupBy('Entity.name')
            ->getQuery()
            ->execute();

        return $hobbies->toArray();
    }
}
