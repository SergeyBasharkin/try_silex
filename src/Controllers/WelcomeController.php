<?php

/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 15.06.17
 * Time: 21:37
 */

namespace Controllers;

use Doctrine\DBAL\Query\QueryBuilder;
use Services\Impls\WelcomeService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController
{
    private $welcomeService;

    public function __construct(WelcomeService $welcomeService)
    {
        $this->welcomeService=$welcomeService;
    }


    public function welcome(Application $app, Request $request)
    {
        $session = $app["session"];
        dump('hello');
        dump($session->get("user"));
        return $app["twig"]->render("welcome.twig", array(
            "test" => $session->get("user"),

        ));
    }


}