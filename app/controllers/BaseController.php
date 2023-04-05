<?php

namespace App\Controllers;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Controller;

abstract class BaseController extends Controller
{
    protected $_isJsonResponse = false;
    protected $code = 200;
    protected $msg = "OK";
    protected $transl;
    protected $logger;
    protected $headerCode = [
        404 => 'Not Found',
        409 => 'Conflict',
        200 => 'OK',
        204 => 'No Content',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        405 => 'Method Not Allowed',
        401 => 'Unauthorized',
        400 => 'Bad Request',
        500 => 'Server Error'
    ];
    protected $validator;
    protected $auth;
    protected $featureChecker;
    protected $mailer;
    protected $simpleView;

    /**
     * @param $dispatcher
     * @return void
     */
    public function beforeExecuteRoute($dispatcher)
    {
        $this->di = $this->getDI();
        $this->logger = $this->di->getLogger();
        $this->transl = $this->view->t;
        $this->validator = $this->di->get('RequestValidator');
        $this->auth = $this->di->get('auth');
        $this->featureChecker = $this->di->get('FeatureChecker');
        $this->mailer = $this->di->get('Mailer');
        $this->simpleView = $this->di->get('SimpleView');

        $this->setJsonResponse();
    }

    /**
     * Prepare Json Response Header
     *
     * @return void
     */
    protected function setJsonResponse()
    {
        $this->view->disable();
        $this->_isJsonResponse = true;
        $this->response->setContentType('application/json', 'UTF-8');
    }

    /**
     * Set response
     *
     * @param Object $msg
     * @param int $code
     *
     * @return void
     */
    protected function setResponse($msg, int $code = 200)
    {
        if ($msg instanceof Model) $msg = $msg->toArray();
        $this->msg = $msg;
        $this->code = $code;
    }

    /**
     * After route executed event
     *
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return void
     */
    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $data = $dispatcher->getReturnedValue();

        if (!is_null($data)) $this->setResponse($data);

        $data = json_encode($this->msg);

        if (is_numeric($this->code)) {
            $this->response->setStatusCode($this->code, $this->headerCode[$this->code]);
        }
        echo $data;
    }
}
