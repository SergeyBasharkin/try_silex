<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 15:00
 */

namespace Controllers;


use Doctrine\DBAL\Query\QueryBuilder;
use Models\User;
use Services\Impls\UserService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Validators\FormValidator;

class LoginController
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function login_get(Application $app)
    {
        return $app["twig"]->render("login.twig", array());
    }

    public function logout(Application $app){
        /** @var Session $session */
        $session = $app["session"];
        $session->remove("user");
    }
    public function login(Application $app, Request $request)
    {

        /** @var FormValidator $validator */
        $validator = $app['validator'];


        /** @var User $user */
        $user = $this->userService->load_user_by_username($request->get('login'));
        dump($user);

        $errors = $validator->validateLoginForm($user, $request);
        if (empty($errors)) {

            /** @var Session $session */
            $session = $app["session"];
            $session->set("user", $user);

            return $app->redirect('/welcome');
        }

        return $app["twig"]->render("login.twig", array(
            "errors" => $errors
        ));
    }

}