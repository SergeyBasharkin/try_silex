<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 26.10.17
 * Time: 13:58
 */

namespace Providers;


use Controllers\CommentsController;
use Controllers\LoginController;
use Controllers\PostsController;
use Controllers\RegistrationController;
use Controllers\WelcomeController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;

class ControllerServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $app)
    {
        $data = $app["jsonLoader"]->load(realpath(__DIR__."/../../config/routes.json"));
        foreach ($data as $controller){
            $name = $this->convertClassName($controller->class);
            $app[$name] = function () use ($app, $controller){
                return new $controller->class($app[$controller->service]);
            };
            foreach ($controller->actions as $action){
                $request = $action->requestMethod;
                $app['controllers']->$request($action->path,$name.':'.$action->method);
            }
        }
        $app['welcome.controller'] = function () use ($app) {
            return new WelcomeController($app['services.welcome']);
        };

//        $app['login.controller'] = function () use ($app) {
//            return new LoginController($app['services.user']);
//        };

        $app['registration.controller'] = function () use ($app) {
            return new RegistrationController($app['services.user']);
        };
        $app['posts.controller'] = function () use ($app) {
            return new PostsController($app['services.posts']);
        };
        $app['comments.controller'] = function () use ($app) {
            return new CommentsController($app['services.comments']);
        };
    }

    private function convertClassName($class){
        $path = explode('\\', $class);
        return array_pop($path);
    }
}