<?php

namespace App\Controllers;

class ContactController extends BaseController
{
    public function sendAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array) $request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'businessName' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required'
        ]);

        $message = $this->mailer->createMessage()
            ->to($request->email)
            ->cc($this->config->adminEmail)
            ->subject("Client Contact")
            ->content($this->simpleView->render('email/contact-us', (Array) $request));

        if ($message->send()) {
            $this->setResponse([ "ok" => true ]);
        } else {
            $this->setResponse([ "error" => "Internal server error" ], 500);
        }
    }
}
