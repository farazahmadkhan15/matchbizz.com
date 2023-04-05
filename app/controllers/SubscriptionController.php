<?php

namespace App\Controllers;

use App\Models\Plan as BusinessPlan;
use App\Models\PlanSubscription;
use App\Models\PlanPayment;
use App\Exceptions\ConflictException;
use App\Exceptions\ServerErrorException;
use App\Exceptions\NotFoundException;

use PayPal\Rest\ApiContext;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan as PayPalPlan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\ShippingAddress;
use PayPal\Api\AgreementStateDescriptor;

class SubscriptionController extends BaseController
{
    public function indexAction()
    {
        //
    }

    public function startAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array) $request, [
            'planId' => 'required|numeric|exists:Plan,id',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        // add validation so only the owner can create a subscription for his business
        // unless the user is an admin

        if (! $this->hasAnApprovedClaim($request->businessProfileId)) {
            $this->setResponse([ "code" => "claim/not-approved-claim-found" ], 400);
            return;
        }

        $previousSubscriptions = PlanSubscription::find([
            "businessProfileId = :businessProfileId: AND status <> 'suspended' AND status <> 'cancelled'",
            "bind" => [
                "businessProfileId" => $request->businessProfileId
            ]
        ]);

        if (count($previousSubscriptions) > 0) {
            foreach ($previousSubscriptions as $previousSubscription) {
                if ($previousSubscription->getStatus() == 'active') {
                    // handle upgrade and downgrade meanwhile just suspend other active subscriptions
                    $previousSubscription->setStatus('suspended');
                } else if ($previousSubscription->getStatus() == 'pendingApproval') {
                    $previousSubscription->setStatus('cancelled');
                }
                $previousSubscription->save();
            }
        }

        $businessPlan = BusinessPlan::findFirst("id = {$request->planId}");
        $apiContext = $this->di->get('PayPalApiContext');
        $paypalPlan = $this->createPayPalPlan($businessPlan, $request->returnUrl, $apiContext);

        $activatedPlan = $this->activatePlan($paypalPlan, $apiContext);

        $startDate = (new \DateTime())->modify('+15 minutes');
        $startDate->setTimezone(new \DateTimeZone("UTC"));
        $agreement = $this->createAgreement($activatedPlan->getId(), $businessPlan, $startDate->format('c'), $apiContext);

        $subscription = new PlanSubscription();
        $subscription->setBusinessProfileId($request->businessProfileId);
        $subscription->setPlanId($businessPlan->getId());
        $subscription->setStatus('pendingApproval');
        $subscription->setStartDate($startDate->format('Y-m-d H:i:s'));

        if (! $subscription->save()) {
            throw new ServerErrorException("subscription/unable-to-save");
        }

        return [ "approvalUrl" => $agreement->getApprovalLink() ];
    }

    private function hasAnApprovedClaim($businessProfileId)
    {
        $claim = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Claim.status",
                "createdAt" => "MAX(Claim.createdAt)"
            ])
            ->from(["Claim" => "App\Models\Claim"])
            ->where("Claim.businessProfileId = :id:", ["id" => $businessProfileId])
            ->groupBy(["Claim.status", "Claim.createdAt"])
            ->getQuery()
            ->execute()
            ->getFirst();

        return $claim->status == 'approved';
    }

    private function createPayPalPlan(BusinessPlan $businessPlan, string $returnUrl, ApiContext $apiContext)
    {
        $cycles = $businessPlan->getBillingCycleNumber();

        $plan = new PayPalPlan();
        $plan->setName(trim($businessPlan->getName()))
            ->setDescription(trim($businessPlan->getName()))
            ->setType($cycles == 0 ? 'INFINITE' : 'FIXED');

        $paymentDefinition = new PaymentDefinition();

        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency($businessPlan->getBillingCycleFrequency())
            ->setFrequencyInterval($businessPlan->getBillingCycleFrequencyInterval())
            ->setCycles(strval($cycles))
            ->setAmount(new Currency([
                'value' => $businessPlan->getCostAmount(),
                'currency' => $businessPlan->getCostCurrencyCode()
            ]));

        try {
            $merchantPreferences = new MerchantPreferences();
            $merchantPreferences
                ->setReturnUrl("{$returnUrl}?success=true")
                ->setCancelUrl("{$returnUrl}?success=false")
                ->setAutoBillAmount("yes")
                ->setInitialFailAmountAction("CONTINUE")
                ->setMaxFailAttempts("3");

            $plan->setPaymentDefinitions(array($paymentDefinition));
            $plan->setMerchantPreferences($merchantPreferences);

            $apiContext = $this->di->get('PayPalApiContext');
            $plan = $plan->create($apiContext);

            return $plan;
        } catch (\Exception $e) {
            throw new ConflictException("paypal/plan-creation-failure", null, $e);
        }
    }

    private function activatePlan(PayPalPlan $paypalPlan, ApiContext $apiContext)
    {
        $patch = new Patch();
        $patch->setOp('replace')
            ->setPath('/')
            ->setValue(new PayPalModel('{ "state":"ACTIVE" }'));

        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);

        try {
            $paypalPlan->update($patchRequest, $apiContext);

            return PayPalPlan::get($paypalPlan->getId(), $apiContext);
        } catch (\Exception $e) {
            throw new ConflictException("paypal/plan-activation-failure", null, $e);
        }
    }

    private function createAgreement(string $paypalPlanId, BusinessPlan $businessPlan, string $startDate, ApiContext $apiContext)
    {
        $agreement = new Agreement();

        $agreement->setName(trim($businessPlan->getName()))
            ->setDescription(trim($businessPlan->getName()))
            ->setStartDate($startDate);

        $paypalPlan = new PayPalPlan();
        $paypalPlan->setId($paypalPlanId);
        $agreement->setPlan($paypalPlan);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            return $agreement->create($apiContext);
        } catch (\Exception $e) {
            throw new ConflictException("paypal/agreement-creation-failure", null, $e);
        }
    }

    public function executeAgreementAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array) $request, [
            'paymentToken' => 'required',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id'
        ]);

        $agreement = $this->executeAgreement($request->paymentToken);

        $subscription = PlanSubscription::findFirst([
            "businessProfileId = :businessProfileId: AND status = 'pendingApproval'",
            "bind" => [ "businessProfileId" => $request->businessProfileId ],
        ]);
        $subscription->setAgreementId($agreement->getId());
        $subscription->setStatus('active');

        if (! $subscription->save()) {
            throw new ServerErrorException("subscription/unable-to-save");
        }

        return $subscription->toArray();
    }

    private function executeAgreement(string $paymentToken)
    {
        try {
            $agreement = new \PayPal\Api\Agreement();
            return $agreement->execute($paymentToken, $this->di->get('PayPalApiContext'));
        } catch (\Exception $e) {
            throw new ConflictException("paypal/agreement-execution-failure");
        }
    }

    public function suspendAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array) $request, [
            'planId' => 'required|numeric|exists:Plan,id',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $subscription = PlanSubscription::findFirst([
            "businessProfileId = :businessProfileId: AND planId = :planId: AND status = 'active'",
            "bind" => [
                "businessProfileId" => $request->businessProfileId,
                "planId" => $request->planId
            ]
        ]);

        if (! $subscription) {
            throw new NotFoundException(
                "suscription/not-found",
                "There is not a registered subscription with the provided businessProfileId and planId"
            );
        }

        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Suspending the agreement");

        try {
            $apiContext = $this->di->get('PayPalApiContext');
            $agreement = Agreement::get($subscription->getAgreementId(), $apiContext);
        } catch (\Exception $e) {
            throw new ServerErrorException("paypal/agreement-retrieval-failure", null, $e);
        }

        if ($agreement->getState() == 'Suspended') {
            throw new ConflictException("suscription/already-suspended");  
        }

        try {
            $agreement->suspend($agreementStateDescriptor, $apiContext);
            $subscription->setStatus('suspended');
            $subscription->save();
        } catch (\Exception $e) {
            throw new ServerErrorException("suscription/unable-to-suspend", null, $e);
        }

        return ['success'];
    }

    public function processIpnAction()
    {
        $ipn = $this->di->get('PayPalIPN');

        $request = $this->request->get();
        // log it anyway
        $this->logger->info("Instant payment notification");
        $this->logger->info(json_encode($request));

        if (! $ipn->verifyIPN()) {
            throw new ServerErrorException("paypal/unable-to-verify-ipn");
        }

        $reasonToSuspend = [
            "recurring_payment_profile_cancel",
            "recurring_payment_suspended_due_to_max_failed_payment"
        ];

        if ($request["txn_type"] == "recurring_payment") {
            if (! isset($request['recurring_payment_id']) || ! isset($request['payment_status']) || ! isset($request["txn_id"])) {
                return;
            }

            $agreementId = $request["recurring_payment_id"];
            $subscription = PlanSubscription::findFirstByAgreementId($agreementId);
            $transactionId = $request["txn_id"];

            $planPayment = PlanPayment::findFirstByTransactionId($transactionId);
            if (! $planPayment) {
                $planPayment = new PlanPayment();
                $planPayment->setPlanSubscriptionId($subscription->getId());
                $planPayment->setTransactionId($transactionId);
            }
            $planPayment->setStatus($request["payment_status"]);
            $planPayment->save();

            if ($request["payment_status"] == "Completed") {
                $subscription->setStatus("active");
                $subscription->save();
            }
        } else if (in_array($request["txn_type"], $reasonToSuspend)) {
            $agreementId = $request["recurring_payment_id"];
            $subscription = PlanSubscription::findFirstByAgreementId($agreementId);
            $subscription->setStatus("Suspended");
            $subscription->save();
        }

        return "OK";
    }
}
