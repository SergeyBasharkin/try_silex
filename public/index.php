<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
use Providers\RepositoryProvider;
use Providers\ServicesProvider;
use Providers\ControllerServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\VarDumperServiceProvider;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Validators\FormValidator;

$defaultFormTheme = 'form_div_layout.html.twig';
$vendorDir = realpath(__DIR__ . '/../vendor');

$dotenv = new Dotenv(__DIR__);
$dotenv->load();


$app = new Silex\Application();


//$app->register(new Silex\Provider\TwigServiceProvider(), array(
//    'twig.path' => __DIR__ . '/../resources/views'
//));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => $_ENV['DB_DRIVER'],
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ),
));

$app['jsonLoader'] = function () {
    return new \Utils\JsonLoader();
};

$app->register(new ControllerServiceProvider());
$app->register(new VarDumperServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new RepositoryProvider());
$app->register(new ServicesProvider());
$app->register(new SessionServiceProvider());
$app['validator'] = function () use ($app) {
    return new FormValidator();
};
//$loader = new Twig_Loader_Filesystem('/path/to/templates');
//$twig = new Twig_Environment($loader, array(
//    'cache' => '/path/to/compilation_cache',
//));


//$app["twig.config"] = function () use ($app) {
//    return array(
//        'cache' => false,
//        'debug' => false
//    );
//};

$app["twig.loader"] = function () use ($app) {
    $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
    $vendorTwigBridgeDir = dirname($appVariableReflection->getFileName());
    $viewsDir = realpath(__DIR__ . '/../resources/views');

    return new Twig_Loader_Filesystem(array(
        $viewsDir,
        $vendorTwigBridgeDir . '/Resources/views/Form',
    ));
};

$app['twig'] = function () use ($app) {
    return new Twig_Environment($app["twig.loader"]);
};

$app['form.engine'] = function () use ($defaultFormTheme, $app){
    return new TwigRendererEngine(array($defaultFormTheme), $app['twig']);
};

$app['twig']->addRuntimeLoader(new Twig_FactoryRuntimeLoader(array(
        TwigRenderer::class => function () use ($app) {
            return new TwigRenderer($app['form.engine']);
        },
    ))
);

$app['twig']->addExtension(new FormExtension());

$app['form.factory'] = function () use ($app) {
    return Forms::createFormFactoryBuilder()
        ->addExtension(new HttpFoundationExtension())
        ->addExtension(new ValidatorExtension(Validation::createValidator()))
        ->getFormFactory();
};

$translator = new Translator('ru');
$translator->addLoader('xlf', new XliffFileLoader());
$translator->addResource(
    'xlf',
    __DIR__ . '/path/to/translations/messages.en.xlf',
    'en'
);
$app['twig']->addExtension(new TranslationExtension($translator));


//$app->post('/login', 'login.controller:login');
//$app->get('/login', 'login.controller:login_get');
//$app->get('/logout', 'login.controller:logout');

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