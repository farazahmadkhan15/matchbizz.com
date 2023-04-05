<?php

namespace App\Security;
use App\Models\User;
use App\Models\UserRole;
use App\Models\BusinessProfile;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;

class Auth
{
    private $modelsManager;

    function __construct(Manager $modelsManager) {
        $this->modelsManager = $modelsManager;
    }

    protected $userId;

    protected $expDate;    

    public function getUser()
    {
        return User::findFirst("id = {$this->userId}");
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getExpDate()
    {
        return $this->expDate;
    }

    public function getRoleId()
    {
        return UserRole::findFirst("userId = {$this->userId}")->getRoleId();
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function setExpDate($expDate)
    {
        $this->expDate = $expDate;

        return $this;
    }

    public function authenticated()
    {
        return $userId != null;
    }

    public function tokenExpired()
    {
        return $this->expDate < time();
    }

    public function isAdmin()
    {
        if ($this->userId == null) {
            return false;
        }

        $user = UserRole::findFirst("userId = {$this->userId}");

        if (! $user) {
            return false;
        }

        return $user->getRoleId() == 1;
    }

    public function isCustomer()
    {
        if ($this->userId == null) {
            return false;
        }

        $user = UserRole::findFirst("userId = {$this->userId}");

        if (! $user) {
            return false;
        }

        return $user->getRoleId() == 2;
    }

    public function isBusinessOwner()
    {
        if ($this->userId == null) {
            return false;
        }

        $user = UserRole::findFirst("userId = {$this->userId}");

        if (! $user) {
            return false;
        }

        return $user->getRoleId() == 3;
    }

    public function isOwner(int $userId)
    {
        if (! $this->isBusinessOwner()) {
            return false;
        }

        $businessProfile = BusinessProfile::findFirstById($userId);
        // if there's no owner yet then take the userId
        return $this->getUserId() == $businessProfile->getUserId();
    }
}
