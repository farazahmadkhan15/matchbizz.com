<?php

namespace App\Controllers;

use App\Models\Plan;
use App\Models\Feature;
use App\Models\PlanFeature;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model\Query;

class BusinessPlanController extends BaseController
{
    public function indexAction()
    {
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
                "Plan.id",
                "Plan.name",
                "Plan.costAmount",
                "Plan.costCurrencyCode",
                "Plan.billingCycleFrequency",
                "Plan.billingCycleFrequencyInterval",
                "Plan.billingCycleNumber"
            ])
            ->from(["Plan" => "App\Models\Plan"]);

        if ($query) {
            $builder->where("Plan.name LIKE :name:", ["name" => "%{$query}%"])
                ->orWhere("Plan.costAmount LIKE :cost:", ["cost" => "%{$query}%"]);
        }

        $builder->andWhere("Plan.deletedAt IS NULL");

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
        $plan = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Plan.id",
                "Plan.name",
                "Plan.costAmount",
                "Plan.costCurrencyCode",
                "Plan.billingCycleFrequency",
                "Plan.billingCycleFrequencyInterval",
                "Plan.billingCycleNumber"
            ])
            ->from(["Plan" => "App\Models\Plan"])
            ->where("Plan.id = :id:", ["id" => $id])
            ->andWhere("Plan.deletedAt IS NULL")
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $plan) {
            $this->setResponse([ "error" => "Plan not found" ], 400);
        } else { 
            $this->setResponse($plan);
        }
    }

    public function retrievePlanFeaturesAction(int $planId)
    {
        if (! Plan::findFirstById($planId)) {
            $this->setResponse([ "error" => "Plan not found" ], 404);
        }

        return PlanFeature::findByPlanId($planId)->toArray();
    }

    public function createAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'name' => 'required',
            'costAmount' => 'required|numeric|min:1',
            'billingCycleFrequency' => 'required|in:Month,Year',
            'billingCycleFrequencyInterval' => 'required|numeric|min:1',
            'features' => 'required|array',
            'features.*.featureId' => 'required|exists:Feature,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $plan = new Plan();
        $plan->setName($request->name);
        $plan->setCostAmount($request->costAmount);
        $plan->setBillingCycleFrequency($request->billingCycleFrequency);
        $plan->setBillingCycleFrequencyInterval($request->billingCycleFrequencyInterval);

        if (! $plan->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) plan',
                'request' => $request,
                'plan' => $plan->toArray(),
            ], 500);
            return;
        }
        $this->updateOrCreate($plan->getId(), $request->features);
        return $this->formatPlan($plan);
    }

    public function updateAction(int $id)
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate(json_decode(json_encode($request), true), [
            'name' => 'required',
            'costAmount' => 'required|numeric|min:1',
            'billingCycleFrequency' => 'required|in:Month,Year',
            'billingCycleFrequencyInterval' => 'required|numeric|min:1',
            'features' => 'required|array',
            'features.*.featureId' => 'required|exists:Feature,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $plan = Plan::findFirstById($id);
        $plan->setName($request->name);
        $plan->setCostAmount($request->costAmount);
        $plan->setBillingCycleFrequency($request->billingCycleFrequency);
        $plan->setBillingCycleFrequencyInterval($request->billingCycleFrequencyInterval);

        if (! $plan->save()) {
            $this->setResponse([
                'error' => 'Cannot save (or update) plan',
                'request' => $request,
                'plan' => $plan->toArray(),
            ], 500);
            return;
        }
        $this->updateOrCreate($plan->getId(), $request->features);
        return $this->formatPlan($plan);
    }

    public function deleteAction(int $id)
    {
        $plan = Plan::findFirstById($id);

        if (! $plan) {
            $this->setResponse([ "error" => "Plan not found" ], 400);
            return;
        }

        $plan->delete();

        $this->setResponse([ "ok" => true ]);
    }

    public function featuresAction()
    {
        return Feature::find();
    }

    private function updateOrCreate(int $planId, array $planFeatures)
    {
        $existentFeatures = PlanFeature::findByPlanId($planId);

        foreach ($planFeatures as $planFeatureData) {
            $planFeature = null;
            foreach ($existentFeatures as $existentFeature) {
                if ($existentFeature->featureId == $planFeatureData->featureId) {
                    $planFeature = $existentFeature;
                    break;
                }
            }

            if (is_null($planFeature)) {
                $planFeature = new PlanFeature();
                $planFeature->setPlanId($planId);
                $planFeature->setFeatureId($planFeatureData->featureId);
            }

            $planFeature->setSpecial($planFeatureData->special ? 1 : 0);
            $planFeature->save();
        }

        $featureIds = array_map(function ($feature) {
            return  $feature->featureId;
        }, $planFeatures);

        $planFeaturesToDelete = PlanFeature::query()
            ->where("planId = {$planId}")
            ->notInWhere("featureId", $featureIds)
            ->execute();

        foreach($planFeaturesToDelete as $planFeatureToDelete) {
            $planFeature->delete();
        }
    }

    public function formatPlan(Plan $plan)
    {
        return $plan ? $plan->appends(['featureIds'])->toArray() : null;
    }
}