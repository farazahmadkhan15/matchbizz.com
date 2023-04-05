<?php
include 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
defined('BASE_PATH') || define('BASE_PATH', realpath('.'));

return [
    'paths' => [
        'migrations' => BASE_PATH . '/db/migrations',
        'seeds'      => BASE_PATH . '/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_' => 'prod',
	'default_environment' => 'prod',
        'dev' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'] ? $_ENV['DB_HOST'] : "34.31.72.69",
            'name' => $_ENV['DB_NAME'] ? $_ENV['DB_NAME'] : 'matchbizz',
            'user' => $_ENV['DB_USERNAME'] ? $_ENV['DB_USERNAME'] : 'matchbizz',
            'pass' => isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : 'matchbizzsecret',
            'port' => $_ENV['DB_PORT'] ? $_ENV['DB_PORT'] : 3306,
        ],
               'prod' => [
            'adapter' => 'mysql',
<<<<<<< HEAD
            'host' => "34.31.72.69",
            'name' => 'matchbizz',
            'user' =>  'matchbizz',
=======
            'host' => 'localhost',
            'name' => 'matchbizz',
            'user' =>  'root',
>>>>>>> 024b3a8 (push from server)
            'pass' =>  'matchbizz',
            'port' => 3306,
        ],
        'qa' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'] ? $_ENV['DB_HOST'] : 'mysql',
            'name' => $_ENV['DB_NAME'] ? $_ENV['DB_NAME'] : 'matchbizz',
            'user' => $_ENV['DB_USERNAME'] ? $_ENV['DB_USERNAME'] : 'matchbizz',
            'pass' => $_ENV['DB_PASSWORD'] ? $_ENV['DB_PASSWORD'] : 'matchbizzsecret',
            'port' => $_ENV['DB_PORT'] ? $_ENV['DB_PORT'] : 3306,
        ],
    ]
];
