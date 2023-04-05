<?php

use Phalcon\Logger\Adapter\File as LogProvider;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Rakit\Validation\Validator;
use Phalcon\Security;
use App\Security\Auth;
use App\Security\FeatureChecker;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Factories\GalleryImageFactory;
use App\Factories\CategoryForestFactory;
use Phalcon\Ext\Mailer;
use Phalcon\Mvc\View\Simple as SimpleView;
use Phalcon\Mvc\View\Engine\Volt;


$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'port' => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset,
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }
    $connection = new $class($params);
    return $connection;
});

$di->setShared('logger', function () {
    $config = $this->getConfig();
    switch ($config->application->logger) {
        case 1:
            if (!file_exists($config->application->logsDir)) {
                mkdir($config->application->logsDir);
                chmod($config->application->logsDir, 0777);
            }
            $logPath = $config->application->logsDir . (new DateTime('now'))->format('Y-m-d') . '.log';
            if (!file_exists($logPath)) {
                file_put_contents($logPath, '');
                chmod($logPath, 0777);
            }
            $logFilename = (new DateTime('now'))->format('Y-m-d') . '.log';
            $logger = new LogProvider($logPath);
            break;
        default:
            $logger = new Phalcon\Logger\Adapter\Stream("php://stderr");
            break;
    }
    $formatter = new \Phalcon\Logger\Formatter\Line(null, 'Y-m-d H:i:s');
    $logger->setFormatter($formatter);

    return $logger;
});


$di->set('router', function () {
    return include APP_PATH . "/config/routes.php";
}, true);

$di->set('dispatcher', function () use ($di) {
    $eventsManager = $di->getShared('eventsManager');

    $eventsManager->attach('dispatch:beforeExecuteRoute', new TranslationPlugin);

    $eventsManager->attach('dispatch:beforeException', new HttpExceptionHandler);
    $eventsManager->attach('dispatch:beforeException', new DispatcherExceptionHandler);

    $eventsManager->attach('dispatch:beforeDispatch', new PreFlightListener);

    $eventsManager->attach('dispatch:beforeExecuteRoute', new TokenListener);

    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('App\Controllers\\');
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});

$di->set('RequestValidator', function () {

    $validator = new Validator;

    $validator->addValidator('exists', new ExistsRule($this->get('modelsManager')));
    $validator->addValidator('unique', new UniqueRule($this->get('modelsManager')));
    $validator->addValidator('greaterThan', new GreaterThanRule());
    $validator->addValidator('greaterThanNullable', new GreaterThanRuleNullable());
    $validator->addValidator('disjoint_spans', new DisjointSpansRule());
    $validator->addValidator('required_only_if', new RequiredOnlyIfRule());

    return $validator;
});

$di->set("security", function () {
    $security = new Security();

    // Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);

    return $security;
}, true);

$di->set("auth", function () {
    return new Auth($this->get('modelsManager'));
}, true);

$di->set("firebase", function () {
    $credentials = (array)$this->get('config')->firebase;
    $serviceAccount = ServiceAccount::fromArray($credentials);
    return (new Factory)
        ->withServiceAccount($serviceAccount)
        ->create();
}, true);

$di->set("GalleryImageFactory", function () {
    return new GalleryImageFactory($this->getConfig(), $this->get('auth'));
}, true);

$di->set("CategoryForestFactory", function () {
    return new CategoryForestFactory($this->get('modelsManager'));
}, true);

$di->set('FeatureChecker', function () {
    return new FeatureChecker($this->get('modelsManager'), $this->get('auth'));
});

$di->set('PayPalApiContext', function () {
    $paypalConfig = $this->getConfig()->paypal;

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $paypalConfig->clientId,
            $paypalConfig->clientSecret
        )
    );

    $apiContext->setConfig([
        'mode' => $paypalConfig->mode,
        'log.LogEnabled' => $paypalConfig->logEnabled,
        'log.FileName' => $paypalConfig->logFileName,
        'log.LogLevel' => $paypalConfig->logLevel,
    ]);

    return $apiContext;
}, true);

$di->set('PayPalIPN', function () {
    return new App\PayPal\PaypalIPN();
}, true);

$di->set('Mailer', function () {
    return new Mailer\Manager($this->getConfig()->emailer->toArray());
}, true);

$di->set('SimpleView', function () {
    $view = new SimpleView();
    $view->setViewsDir('../app/views/');
    $view->registerEngines(['.volt' => 'VoltService']);

    return $view;
}, true);

$di->set('VoltService', function ($view, $di) {
    $volt = new Volt($view, $di);
    $volt->setOptions($this->getConfig()->view->toArray());
    return $volt;
});
