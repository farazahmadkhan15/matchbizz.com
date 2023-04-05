<?php

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;

/**
 * Class PreFlightListener
 * @package App\Listener
 * @property Request $request
 * @property Response $response
 */
class PreFlightListener extends Injectable
{
    const ORIGINS_ALLOWED = [
        /*'http://localhost:4200',
        'http://localhost:8080',
        'http://localhost:8081',
        'http://matchbizz.test:4200',*/
    ];

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
        $di = $dispatcher->getDI();
        $request = $di->get('request');
        $response = $di->get('response');

        if ($this->isCorsRequest($request)) {
            $response
                // ->setHeader('Access-Control-Allow-Origin', $this->getOrigin($request))
                // ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
                ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization');
        }

        if ($this->isPreflightRequest($request)) {
            $response->setStatusCode(200, 'OK')->send();
            exit;
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request)
    {
        return !empty($request->getHeader('Origin')) && !$this->isSameHost($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isPreflightRequest(Request $request)
    {
        return $this->isCorsRequest($request)
            && $request->getMethod() === 'OPTIONS'
            && !empty($request->getHeader('Access-Control-Request-Method'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSameHost(Request $request)
    {
        return $request->getHeader('Origin') === $this->getSchemeAndHttpHost($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getSchemeAndHttpHost(Request $request)
    {
        return $request->getScheme() . '://' . $request->getHttpHost();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getOrigin(Request $request)
    {
        $origin = $request->getHeader('Origin');
        return in_array($origin, self::ORIGINS_ALLOWED) ? $origin : '*';
    }
}
