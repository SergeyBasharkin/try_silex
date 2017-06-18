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

    public function login(Application $app, Request $request)
    {
        /** @var string $login */
        $login = $request->get("login");

        /** @var string $password */
        $password = $request->get("password");

        /** @var User $user */
        $user = $this->userService->load_user_by_username($login);

        $errors = [];
        if ($user === null) {
            $errors[] = "user not found";
        } elseif ($login !== $user->getEmail() && $password!== $user->getPassword()) {
            $errors[] = "bad credentials";
        }

        if (empty($errors)) {

            /** @var Session $session */
            $session = $app["session"];
            $session->set("user", $user);

            return $app->redirect('/posts');
        }

        return $app["twig"]->render("login.twig", array(
            "errors" => $errors
        ));
    }

}