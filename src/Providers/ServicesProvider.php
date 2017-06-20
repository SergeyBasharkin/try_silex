<?php

namespace Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Repositories\Impls\CommentsRepository;
use Services\Impls\CommentsService;
use Services\Impls\PostsService;
use Services\Impls\UserService;
use Services\Impls\WelcomeService;

/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 15:51
 */
class ServicesProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app
     * @internal param Container $pimple A container instance
     */
    public function register(Container $app)
    {
        $app["services.user"] = function () use ($app) {
            return new UserService($app, $app["repositories.user"]);
        };

        $app["services.welcome"] = function () use ($app) {
            return new WelcomeService($app, $app["repositories.welcome"]);
        };
        $app["services.posts"] = function () use ($app) {
            return new PostsService($app, $app["repositories.posts"]);
        };
        $app["services.comments"] = function () use ($app) {
            return new CommentsService($app, $app["repositories.comments"]);
        };
    }
}