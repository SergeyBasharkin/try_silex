<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\WelcomeController;

$app = new Silex\Application();


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../resources/views'
));

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());

$app['welcome.controller'] = function () use ($app){
    return new WelcomeController($app);
};



$app->get('/', 'welcome.controller:welcome');
$app['debug'] = true;

$app->run();