<?php
$app = new \Slim\Slim([
    'sp.version' => '4.0.1',
    'sp.version_clear' => '400',
    'db.user' => 'root',
    'db.password' => 'mysqlpass',
    'db.name' => 'dbname',
    'demo_mod' => false,
    'view' => new \SP\Views\MyUltimateView(),
    'templates.path' => __DIR__ . '/views',
    'db.host' => 'localhost',
    'db.port' => 3306,
    'db.encoding' => 'utf8',
    'php.timezone' => 'America/Bogota',
    'php.error-reporting' => E_ALL | E_STRICT,
    //'php.error-reporting' => E_ALL,
    'middleware.authentication' => [
        'filter_mode' => \SP\Middleware\AbstractFilterableMiddleware::EXCLUSION,
        'route_names' => ['login', 'doLogin', 'doLogout'],
    ],
    'middleware.authorization' => [
        'filter_mode' => \SP\Middleware\AbstractFilterableMiddleware::INCLUSION,
        'route_names' => ['restricted'],
        'route_group_mappings' => [
            'restricted' => ['usr', 'adm', 'dj'],
        ],
    ],
]);
