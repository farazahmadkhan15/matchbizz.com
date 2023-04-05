<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use App\Exceptions;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class DispatcherExceptionHandler extends Plugin
{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception) {
        if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
            $dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
            return false;
        }
    }
}