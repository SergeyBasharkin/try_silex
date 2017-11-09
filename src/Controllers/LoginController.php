<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 15:00
 */

namespace Controllers;


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


    public function login_get(Application $app, Request $request)
    {
        return $app["twig"]->render("login.twig", array());
    }

    public function login(Application $app, Request $request)
    {

        /** @var FormValidator $validator */
        $validator = $app['validator'];

        /** @var User $user */
        $user = $this->userService->load_user_by_username($request->get('login'));
        $errors = $validator->validateLoginForm($user, $request);
        if (empty($errors)) {

            /** @var Session $session */
            $session = $app["session"];
            $session->set("user", $user);

            return $app->redirect('/');
        }

        return $app["twig"]->render("login.twig", array(
            "errors" => $errors
        ));
    }

    public function logout(Application $app){
        /** @var Session $session */
        $session = $app["session"];
        $session->remove("user");
        return $app["twig"]->render("login.twig", array());
    }

}