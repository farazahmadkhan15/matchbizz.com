<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

defined('APP_PATH')
|| define('APP_PATH', realpath(dirname(__FILE__)));

require  realpath(dirname(__DIR__))."/vendor/autoload.php";

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/plugins/',
        APP_PATH . '/tasks/',
        APP_PATH . '/libraries/',
    ]
)->registerNamespaces(
    [
        'App\Controllers'     => APP_PATH . '/controllers/',
        'App\Models'          => APP_PATH . '/models/',
        'App\Libraries'       => APP_PATH . '/libraries/',
    ]
)->register();

// Using the CLI factory default services container
$di = new FactoryDefault();
$config = include  __DIR__ . '/config/config.php';
require  __DIR__ . '/config/services.php';

// Router
$di->setShared(
    'router', function () {
        return new Phalcon\CLI\Router();
    }
);

// Dispatcher
$di->setShared(
    'dispatcher', function () {
        return new Phalcon\CLI\Dispatcher();
    }
);

//this dispacher is used por controller handler
$di->set(
    'controllerDispatcher', function () {
        $dispatcher = new Phalcon\Mvc\Dispatcher();
        return $dispatcher;
    }
);

//Gets the mainapp config
$di->set("config", $config);


// Create a console application
$console = new ConsoleApp();

$console->setDI($di);



/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}