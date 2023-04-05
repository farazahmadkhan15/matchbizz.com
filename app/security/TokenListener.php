<?php

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Firebase\JWT\JWT;
use Phalcon\Mvc\User\Plugin;
use App\Models\InvalidToken;
use App\Exceptions\UnauthorizedException;

class TokenListener extends Plugin
{
    protected $controllerActionWhiteList = [
        'index' => '*',
        'error' => '*',
        'security' => '*',
        'registration' => '*',
        'filter' => [ 'retrieveValue', 'retrieveRange' ],
        'category' => [ 'autocomplete', 'roots', 'children' ],
        'subscription' => [ 'processIpn' ],
        'contact' => ['send'],
        'business-plan' => ['index', 'features', 'retrievePlanFeatures']
    ];

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if ($this->isWhiteListed($dispatcher->getControllerName(), $dispatcher->getActionName())) {
            return true;
        }

        $token = $this->request->getHeader('Authorization');

        $jwtConfig = $this->config->jwt->toArray();
        $auth = $this->di->get('auth');

        if ($token) {
            try {
                $payload = JWT::decode($token, $jwtConfig['secret'], $jwtConfig['algorithm']);
            } catch (\UnexpectedValueException $e) {
                $token = null;
            }
        }

        if (! $token) {
            throw new UnauthorizedException("token/required", "The token is required");
        }

        if ($payload->exp_date < time()) {
            throw new UnauthorizedException("token/expired", "The token has already expired");
        }

        $invalidToken = InvalidToken::findFirst([
            "token = :token:",
            "bind" => [
                "token" => $token,
            ]
        ]);

        if ($invalidToken) {
            throw new UnauthorizedException("token/removed", "The token has already been removed");
        }

        $auth->setUserId($payload->id);
        $auth->setExpDate($payload->exp_date);
    }

    private function isWhiteListed($controller, $action)
    {
        if (! isset($this->controllerActionWhiteList[$controller])) {
            return false;
        }

        $allowedActions = $this->controllerActionWhiteList[$controller];
        if ($allowedActions == "*") {
            return true;
        }

        return in_array($action, $allowedActions);
    }

}
