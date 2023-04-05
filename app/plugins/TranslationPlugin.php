<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Translate\Adapter\NativeArray;

class TranslationPlugin extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $language     = $language = $this->request->getBestLanguage();
        $translateDir = APP_PATH.'/messages/';

        if (file_exists($translateDir . $language . '.php')) {
            include $translateDir . $language . '.php';
        } else {
            include $translateDir . 'es.php';
        }
        $this->view->t = new NativeArray(['content' => $messages]);
    }
}