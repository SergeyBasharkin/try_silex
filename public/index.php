<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\CommentsController;
use Controllers\LoginController;
use Controllers\PostsController;
use Controllers\RegistrationController;
use Controllers\WelcomeController;
use Dotenv\Dotenv;
use Providers\RepositoryProvider;
use Providers\ServicesProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\VarDumperServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Validators\FormValidator;

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
$app->register(new SessionServiceProvider());

$app['validator'] = function () use ($app) {
    return new FormValidator();
};

$app['welcome.controller'] = function () use ($app) {
    return new WelcomeController($app['services.welcome']);
};

$app['login.controller'] = function () use ($app) {
    return new LoginController($app['services.user']);
};

$app['registration.controller'] = function () use ($app) {
    return new RegistrationController($app['services.user']);
};
$app['posts.controller'] = function () use ($app) {
    return new PostsController($app['services.posts']);
};
$app['comments.controller'] = function () use ($app) {
    return new CommentsController($app['services.comments']);
};


$app->post('/login', 'login.controller:login');
$app->get('/login', 'login.controller:login_get');
$app->get('/logout', 'login.controller:logout');

$app->get('/registration', "registration.controller:registration_get");
$app->post('/registration', "registration.controller:registration_post");

$app->get('/', 'posts.controller:get_all_posts');
$app->get('/posts/{id}', 'posts.controller:get_post');
$app->get('/posts/users/{user_id}', 'posts.controller:get_user_posts');
$app->post('/post', 'posts.controller:post_post')->before(
    function () use ($app) {
        if ($app['session']->get('user') === null) {
            return $app->redirect('/login');
        }
    }
);
$app->get('/post', 'posts.controller:show_post_form')->before(
    function () use ($app) {
        if ($app['session']->get('user') === null) {
            return $app->redirect('/login');
        }
    }
);
$app->post('/posts/{post_id}/comments', 'comments.controller:add_comment')->before(
    function () use ($app) {
        if ($app['session']->get('user') === null) {
            return $app->redirect('/login');
        }
    }
);
$app['debug'] = true;


$app->run();