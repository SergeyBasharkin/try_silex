<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 16:53
 */

namespace Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Repositories\Impls\UserRepository;
use Repositories\Impls\WelcomeRepository;

class RepositoryProvider implements ServiceProviderInterface
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
        $app["repositories.welcome"] = function () use ($app) {
            return new WelcomeRepository($app["db"]);
        };
        $app["repositories.user"] = function () use ($app) {
            return new UserRepository($app["db"]);
        };
    }
}