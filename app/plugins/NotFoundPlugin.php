<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class NotFoundPlugin extends Plugin
{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception) {
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:

                $this->response->setStatusCode(404, "not found");
                $this->response->setContentType('application/json', 'UTF-8');
                $this->response->setJsonContent(["error" => $exception->getMessage()])
                    ->send();
                exit;
            }
        }
    }
}