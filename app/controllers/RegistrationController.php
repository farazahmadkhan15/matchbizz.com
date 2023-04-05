<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserRole;

class RegistrationController extends BaseController
{
    public function registerUserAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array)$request, [
            'username' => 'required|unique:User,username',
            'email'    => 'required|email|unique:User,email',
            'password' => 'required',
            'roleId'   => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
 
        // Store the password hashed
        $user = new User();
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $user->setPassword($this->security->hash($request->password));
        if(!$user->save()){
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
            return;
        }
        $userRole = new userRole();
        $userRole->setUserId($user->id);
        $userRole->setRoleId($request->roleId);
        if(!$userRole->save()){
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
            return;
        }
        return [ "user" => $user->toArray() ];
    }
}