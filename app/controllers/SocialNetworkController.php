<?php

namespace App\Controllers;

use App\Models\SocialNetwork;

class SocialNetworkController extends BaseController
{
    public function indexAction()
    {
        return SocialNetwork::find()->toArray();
    }

    public function retrieveAction(int $id)
    {
        $social = SocialNetwork::findFirstById($id);

        if (! $social) {
            $this->setResponse([ "error" => "Social Network Not Found" ], 404);
        } else {
            $this->setResponse($social->toArray());
        }
    }

    public function createAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'name' => 'required',
            'baseUrl' => 'required',
        ]);
        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $social = new SocialNetwork();
        $social->setName($request->name);
        $social->setBaseUrl($request->baseUrl);

        if (! $social->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($social->toArray());
        }
    }

    public function updateAction(int $id)
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'name' => 'required',
            'baseUrl' => 'required',
        ]);
        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $social = SocialNetwork::findFirstById($id);

        if (! $social) {
            $this->setResponse([ "error" => "Social Network Not Found" ], 404);
            return;
        }

        $social->setName($request->name);
        $social->setBaseUrl($request->baseUrl);

        if (! $social->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($social->toArray());
        }
    }

    public function deleteAction(int $id)
    {
        $social = SocialNetwork::findFirstById($id);

        if (! $social) {
            $this->setResponse([ "error" => "Social Network Not Found" ], 404);
        } else  {
            $social->delete();
            $this->setResponse([ "ok" => true ]);
        }
    }
}
