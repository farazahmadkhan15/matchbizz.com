<?php

namespace App\Controllers;

use App\Models\SocialNetworkAccount;

class SocialNetworkAccountController extends BaseController
{
    public function indexAction()
    {
        return SocialNetworkAccount::find()->toArray();
    }

    public function retrieveAction(int $businessProfileId)
    {
        $this->featureChecker->check($businessProfileId,'link-your-social-networks');

        $social = SocialNetworkAccount::find("businessProfileId = {$businessProfileId}");

        if (! $social) {
            $this->setResponse([ 'error' => 'Social Network Not Found' ], 404);
        } else {
            $this->setResponse($social->toArray());
        }
    }

    public function updateAction(int $businessProfileId)
    {
        $this->featureChecker->check($businessProfileId,'link-your-social-networks');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $request = $this->request->getJsonRawBody();
        $socialNetworks = $request->accounts;

        if (count($socialNetworks) > 0) {
            foreach($socialNetworks as $social) {
                $socialNetworkAccount = SocialNetworkAccount::findFirstWithTrashed([
                    'businessProfileId = :businessProfileId: AND socialNetworkId = :socialNetworkId:',
                    'bind' => [
                        'businessProfileId' => $businessProfileId,
                        'socialNetworkId' => $social->socialNetworkId,
                    ],
                ]);

                $segment = trim($social->urlSegment);

                if ($socialNetworkAccount && empty($segment)) {
                    $socialNetworkAccount->delete();
                    continue;
                }

                if (! $socialNetworkAccount) {
                    $socialNetworkAccount = new SocialNetworkAccount();
                    $socialNetworkAccount->setBusinessProfileId($businessProfileId);
                    $socialNetworkAccount->setSocialNetworkId($social->socialNetworkId);
                }
                $socialNetworkAccount->setUrlSegment($segment);

                if (is_null($socialNetworkAccount->getDeletedAt())) {
                    $socialNetworkAccount->save();
                } else {
                    $socialNetworkAccount->restore();
                }
            }
        }

        $socialNetworkAccounts = SocialNetworkAccount::findWithTrashed([
            'businessProfileId = :businessProfileId:',
            'bind' => ['businessProfileId' => $businessProfileId],
        ]);

        return [
            'ok' => true,
            'networks' => $socialNetworkAccounts->toArray()
        ];
    }
}
