<?php

namespace App\Controllers;

use App\Models\Service;
use App\Models\BusinessProfile;
use App\Models\Category;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class ServiceController extends BaseController
{
    public function indexAction()
    {
        //
    }

    public function retrieveAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirst("id = '{$businessProfileId}'")) {
            $this->setResponse([ "error" => "Business Profile not found" ], 404);
            return;
        }

        $categories = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Category.id",
                "Category.name",
                "Category.parentCategoryId",
                "Category.deletedAt",
                "icon" => "Icons.code"
            ])
            ->from(["Category" => "App\Models\Category"])
            ->join("App\Models\Service", "Service.categoryId = Category.id AND Service.deletedAt IS NULL", "Service")
            ->join("App\Models\Icons", "Category.IconId = Icons.id", "Icons", "LEFT")
            ->where("Service.businessProfileId = :businessProfileId:",["businessProfileId" => $businessProfileId])
            ->andWhere("Category.deletedAt IS NULL")
            ->getQuery()
            ->execute();

        if (! $categories) {
            $this->setResponse([ "error" => "Services Not Found" ], 404);
        } else {
            return $this->di->get('CategoryForestFactory')
                ->create($categories->toArray());
        }
    }

    public function createAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirst("id = '{$businessProfileId}'")) {
            $this->setResponse([ "error" => "Business Profile not found" ], 404);
            return;
        }

        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'categoryIds' => 'required|array',
            'categoryIds.*' => 'required|numeric|exists:Category,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $transactionManager = new TransactionManager(); 
        $transaction = $transactionManager->get();

        foreach($request->categoryIds as $categoryId){
           $service = new Service();
           $service->setTransaction($transaction);
           $service->setBusinessProfileId($businessProfileId);
           $service->setCategoryId($categoryId);
           $service->create();
        }

        if (!$transaction->commit()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse(["ok" => true], 200);
        }
    }

    public function deleteAction(int $businessProfileId)
    {
        if (! BusinessProfile::findFirst("id = '{$businessProfileId}'")) {
            $this->setResponse([ "error" => "Business Profile not found" ], 404);
            return;
        }

        $services = json_decode($this->request->get('categoryIds'));
        $id_in = implode(',', $services);

        $services = Service::find("businessProfileId = {$businessProfileId} AND categoryId IN ({$id_in})");

        if (! $services) {
            $this->setResponse([ "error" => "Bad request"], 400);
        } else {
            foreach($services as $service) {
                $service->delete();                
            }
            $this->setResponse(["ok" => true], 200);
        }
    }
}
