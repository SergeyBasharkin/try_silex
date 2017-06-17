<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 15:00
 */

namespace Controllers;


use Doctrine\DBAL\Query\QueryBuilder;
use Services\Impls\UserService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoginController
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService=$userService;
    }


    public function login_get(Application $app){
        return $app["twig"]->render("login.twig",array());
    }

    public function login(Application $app, Request $request){
        $res = $this->userService->load_user_by_username($request->get("login"));
        dump($res);
        return $app["twig"]->render("login.twig", array(
            "req"=>"1"
        ));
    }

}