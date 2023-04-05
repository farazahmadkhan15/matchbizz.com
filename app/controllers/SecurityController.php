<?php

namespace App\Controllers;

use App\Exceptions\ForbiddenException;
use App\Models\InvalidToken;
use App\Models\User;
use App\Models\UserRole;
use \Firebase\JWT\JWT;

const ADMIN_ROLE_ID = 1;

class SecurityController extends BaseController
{
    public function adminSignInAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array) $request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse(["error" => $validation->errors()->toArray()], 400);
            return;
        }

        $user = $this->modelsManager
            ->createBuilder()
            ->columns([
                "User.id",
                "User.password",
                "name" => "User.username",
                "roleId" => "UserRole.roleId",
            ])
            ->from(["User" => "App\Models\User"])
            ->join("App\Models\UserRole", "UserRole.userId = User.id", "UserRole")
            ->where("User.username = :username: AND UserRole.roleId = :adminRoleId:", [
                "username" => $request->username,
                "adminRoleId" => ADMIN_ROLE_ID,
            ])
            ->getQuery()
            ->execute()
            ->getFirst();

        if ($user) {
            if ($this->security->checkHash($request->password, $user->password)) {

                $jwtConfig = $this->config->jwt->toArray();

                $payload = [
                    "id" => $user->id,
                    "exp_date" => time() + $jwtConfig['expires_in'],
                ];

                return [
                    'token' => JWT::encode($payload, $jwtConfig['secret']),
                    'expires_in' => $jwtConfig['expires_in'],
                    'user' => $user->toArray(),
                ];
            }
        } else {
            // To protect against timing attacks. Regardless of whether a user exists or not, the script will take roughly the same amount as it will always be computing a hash.
            $this->security->hash(rand());
        }

        // The validation has failed
        $this->setResponse([
            "code" => "auth/invalid-credentials",
            "message" => "The provided credentials doesn't correspond to any admin account",
        ], 401);
    }

    public function signOutAction()
    {
        $token = $this->request->getHeader('Authorization');

        $validation = $this->validator->validate(['Authorization' => $token], [
            'Authorization' => 'required|unique:InvalidToken,token',
        ]);

        if ($validation->fails()) {
            $this->setResponse(["error" => $validation->errors()->toArray()], 400);
            return;
        }

        $jwtConfig = $this->config->jwt->toArray();

        try {
            $payload = JWT::decode($token, $jwtConfig['secret'], $jwtConfig['algorithm']);
        } catch (\UnexpectedValueException $e) {
            $this->setResponse(["error" => "Invalid token"], 401);
            return;
        }

        $invalidToken = new InvalidToken();
        $invalidToken->setToken($token);
        $invalidToken->setExpiration(date('Y-m-d H:i:s', $payload->exp_date));
        $invalidToken->save();

        return ["ok"];
    }

    public function getTokenAction()
    {
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((array) $request, [
            'idToken' => 'required',
            'userUid' => 'required',
            'roleId' => 'required|exists:Role,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse(["error" => $validation->errors()->toArray()], 400);
            return;
        }

        $userUid = $request->userUid;
        $roleId = $request->roleId;
        $planId = empty($request->planId) ? 0 : $request->planId;

        $firebaseAuth = $this->di->get('firebase')->getAuth();
        $firebaseUser = $firebaseAuth->getUser($userUid);

        try {
            $verifiedIdToken = $firebaseAuth->verifyIdToken($request->idToken);
        } catch (\Exception $e) {
            throw new ForbiddenException("auth/invalid-firebase-token", "The provided id token is not valid.");
        }

        $user = User::findFirst([
            "email = :email:",
            "bind" => [
                "email" => $firebaseUser->email,
            ],
        ]);

        $isUserSigningUp = !$user;

        if ($isUserSigningUp) {
            $user = new User();
            $user->setEmail($firebaseUser->email);
            $user->setSelectedPlanId($planId);

            if (!$user->save()) {
                $this->setResponse(["error" => "Unable to create user"], 400);
                return;
            }

            $userRole = new UserRole();
            $userRole->setUserId($user->id);
            $userRole->setRoleId($roleId);

            if (!$userRole->save()) {
                $this->setResponse(["error" => "Unable to create rol"], 400);
                return;
            }
        }

        $role = $this->modelsManager
            ->createBuilder()
            ->columns(["Role.*"])
            ->from(["Role" => "App\Models\Role"])
            ->join("App\Models\UserRole", "UserRole.roleId = Role.id", "UserRole")
            ->where("UserRole.userId = :userId:", ["userId" => $user->getId()])
            ->getQuery()
            ->execute()
            ->getFirst();

        if ($role->getId() != $roleId) {
            $this->setResponse(["code" => "rol-mismatch", "message" => "User doesn't have the provided rol"], 400);
            return;
        }

        $jwtConfig = $this->config->jwt->toArray();

        $payload = [
            "id" => $user->getId(),
            "exp_date" => time() + $jwtConfig['expires_in'],
        ];

        return [
            'token' => JWT::encode($payload, $jwtConfig['secret']),
            'expires_in' => $jwtConfig['expires_in'],
            'user' => [
                "id" => $user->getId(),
                "name" => $firebaseUser->displayName,
                "roleId" => $role->getId(),
                'profileId' => $this->getProfileId($user->getId()),
                "activePlanId" => $this->getPlanId($user->getId()),
                'claimStatus' => $this->getClaimStatus($user->getId()),
                'selectedPlanId' => $user->getSelectedPlanId()
            ],
        ];
    }

    public function isValidEmailAction(string $email)
    {
        $user = User::findFirst([
            "email = :email:",
            "bind" => [
                "email" => $email,
            ],
        ]);

        return $user != false;
    }

    private function getProfileId(int $userId)
    {
        $profileIds = $this->modelsManager
            ->createBuilder()
            ->columns([
                "CustomerProfileId" => "CustomerProfile.id",
                "BusinessProfileId" => "BusinessProfile.id",
            ])
            ->from(["User" => "App\Models\User"])
            ->join("App\Models\CustomerProfile", "CustomerProfile.userId = User.id AND CustomerProfile.deletedAt IS NULL", "CustomerProfile", "LEFT")
            ->join("App\Models\BusinessProfile", "BusinessProfile.userId = User.id AND BusinessProfile.deletedAt IS NULL", "BusinessProfile", "LEFT")
            ->where("User.id = :userId:", ["userId" => $userId])
            ->getQuery()
            ->execute()
            ->getFirst();

        return !is_null($profileIds['CustomerProfileId']) ?
        $profileIds['CustomerProfileId'] :
        $profileIds['BusinessProfileId'];
    }

    private function getPlanId(int $userId)
    {
        $result = $this->modelsManager
            ->createBuilder()
            ->columns([
                "PlanSubscription.planId",
            ])
            ->from(["User" => "App\Models\User"])
            ->join("App\Models\BusinessProfile", "BusinessProfile.userId = User.id", "BusinessProfile")
            ->join(
                "App\Models\PlanSubscription",
                "BusinessProfile.id = PlanSubscription.businessProfileId AND PlanSubscription.status = 'active'",
                "PlanSubscription"
            )
            ->where("User.id = :userId:", ["userId" => $userId])
            ->getQuery()
            ->execute()
            ->getFirst();

        return (!$result ? null : $result->planId);
    }

    private function getClaimStatus(int $userId)
    {
        $claim = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Claim.status",
                "Claim.deletedAt",
            ])
            ->from(["Claim" => "App\Models\Claim"])
            ->join("App\Models\UserRole", "UserRole.userId = Claim.userId", "UserRole")
            ->where("UserRole.roleId = 3")
            ->andWhere("Claim.userId = :userId:", ["userId" => $userId])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (!$claim) {
            return null;
        }

        if (!is_null($claim->deletedAt)) {
            return 'deleted';
        }

        return $claim->status;
    }

}
