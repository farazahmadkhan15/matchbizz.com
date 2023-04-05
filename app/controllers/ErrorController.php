<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

/**
   Cannot use Phalcon methods to set the response here when having xdebug activated
   because the stacktrace is automatically appended, and it's not being reset by the
   setContent method of the request object. So php boy it's all yours
  **/
class ErrorController extends Controller
{
    public function unauthorizedAction($e = null)
    {
        $this->setResponse($e, 'Unauthorized', 401);
    }

    public function forbiddenAction($e = null)
    {
        $this->setResponse($e, 'Permission Denied', 403);
    }

    public function notFoundAction($e = null)
    {
        $this->setResponse($e, 'Not Found', 404);
    }

    public function conflictAction($e = null)
    {
        $this->setResponse($e, 'Confict', 409);
    }

    public function serverErrorAction($e = null)
    {
        $this->setResponse($e, 'Server Error', 500);
    }

    private function setResponse(\Exception $e = null, string $defaultMessage, int $httpStatusCode)
    {
        if (! is_null($e)) {
            $this->logException($e);
        }

        // now set the response
        ob_end_clean(); // remove xdebug stacktrace
        http_response_code($httpStatusCode); // force http code set
        header('Content-Type: application/json'); // force content type set

        echo json_encode([
            "code" => is_null($e) ? "unknown" : $e->getCodeName(),
            "message" =>  is_null($e) ? $defaultMessage : $e->getMessage()
        ]);
    }

    private function logException(\Exception $e)
    {
        $logger = $this->di->get('logger');
        while (! is_null($e)) {
            $logger->error("Exception: ".$e);
            $e = $e->getPrevious();
        }
    }
}