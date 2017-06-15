<?php

/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 15.06.17
 * Time: 21:37
 */
namespace Controllers;

use Silex\Application;

class WelcomeController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function welcome(){
        return $this->app["twig"]->render("welcome.twig");
    }


}