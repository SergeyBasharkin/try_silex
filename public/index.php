<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\LoginController;
use Controllers\WelcomeController;
use Dotenv\Dotenv;
use Providers\RepositoryProvider;
use Providers\ServicesProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\VarDumperServiceProvider;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();


$app = new Silex\Application();


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../resources/views'
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => $_ENV['DB_DRIVER'],
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ),
));


$app->register(new AssetServiceProvider(), array(
        'assets.version' => ''
    )
);

$app->register(new VarDumperServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new RepositoryProvider());
$app->register(new ServicesProvider());


$app['welcome.controller'] = function() use ($app) {
    return new WelcomeController($app['services.welcome']);
};

$app['login.controller'] = function() use ($app) {
    return new LoginController($app['services.user']);
};

$app->get('/', 'welcome.controller:welcome');
$app->post('/login', 'login.controller:login');
$app->get('/login', 'login.controller:login_get');

$app['debug'] = true;


$app->run();