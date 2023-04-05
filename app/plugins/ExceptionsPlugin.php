<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use App\Exceptions;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class HttpExceptionHandler extends Plugin
{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception) {
        if ($exception instanceof Exceptions\UnauthorizedException) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'unauthorized',
                "params" => array($exception)
            ]);
        } else if ($exception instanceof Exceptions\ForbiddenException) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'forbidden',
                "params" => array($exception)
            ]);
        } else if ($exception instanceof Exceptions\ConflictException) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'conflict',
                "params" => array($exception)
            ]);
        } else if ($exception instanceof Exceptions\ServerErrorException) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'serverError',
                "params" => array($exception)
            ]);
        } else if ($exception instanceof Exceptions\NotFoundException) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'notFound',
                "params" => array($exception)
            ]);
        }
    }
}