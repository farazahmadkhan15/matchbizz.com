<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Allow Cross Origin
$cors = function(){
    $allowed = [
        'http://localhost:4200',
        'http://localhost:8080',
        'http://localhost:8081',
    ];

    // when testing from postman HTTP_ORIGIN is unset 
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
    if ($origin) {
        if(in_array($origin, $allowed)) {
            header('Access-Control-Allow-Credentials: true');
        } else {
            $origin = '*';
        }
        header('Access-Control-Allow-Origin: ' . $origin);
    }
};
$cors();
unset($cors);

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/views/',
        APP_PATH . '/plugins/',
        APP_PATH . '/libraries/',
        APP_PATH . '/factories/',
        APP_PATH . '/tasks/',
        APP_PATH . '/validations/',
        APP_PATH . '/listeners/',
        APP_PATH . '/security/',
        APP_PATH . '/exceptions/',
        APP_PATH . '/request-validations/',
        APP_PATH . '/paypal',
        BASE_PATH . '/cache/',
        BASE_PATH . '/crons/',
        BASE_PATH . '/logs/',
        BASE_PATH . '/tests/',
        BASE_PATH . '/tests/Test/',
    ]
)->registerNamespaces(
    [
        'App\Controllers'     => APP_PATH . '/controllers/',
        'App\Models'          => APP_PATH . '/models/',
        'App\Libraries'       => APP_PATH . '/libraries/',
        'App\Factories'       => APP_PATH . '/factories/',
        'App\Exceptions'       => APP_PATH . '/exceptions/',
        'Test'                => BASE_PATH . '/tests/Test/',
        'App\Validations'     => APP_PATH . '/validations/',
        'App\Security'        => APP_PATH . '/security/',
        'App\RequestValidations'     => APP_PATH . '/request-validations/',
        'App\PayPal' => APP_PATH . '/paypal',
    ]
)->register();

// Create a DI
$di = new FactoryDefault();

// Services and Config load
require APP_PATH . '/config/services.php';

// Setup the view component
$di->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

// Setup a base URI
$di->set(
    'url',
    function () {
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    }
);

// Load external dependencies
$autoloadFilepath = BASE_PATH . '/vendor/autoload.php';

if (!file_exists($autoloadFilepath)) {
    echo "External dependencies not satisfied. Please run composer update.";
    return;
}

require $autoloadFilepath;

/**
 * Environment variables
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$application = new Application($di);

try {
    // Handle the request
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
   echo 'Exception: ', $e->getMessage();
}
