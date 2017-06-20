<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 12:20
 */

namespace Controllers;


use Models\User;
use Services\Impls\UserService;
use Silex\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Validators\FormValidator;

class RegistrationController
{
    private $userService;

    /**
     * RegistrationController constructor.
     * @param $userService UserService
     */
    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    public function registration_get(Application $app)
    {
        return $app["twig"]->render("registration.twig", array("result" => ""));

    }

    public function registration_post(Application $app, Request $request)
    {


        /** @var FormValidator $validator */
        $validator = $app["validator"];
        $errors = $validator->validateRegistrationForm($request);
        $result = false;
        if (empty($errors)) {
            $user = new User();
            $user->setEmail($app->escape($request->get("email")));
            $user->setPassword(password_hash($request->get("password"), PASSWORD_BCRYPT));
            $user->setAvatar($request->files->get("file"));

            $result = $this->userService->saveUser($user);
        }
        return $app['twig']->render("registration.twig", array(
            "errors" => $errors,
            "result" => $result
        ));
    }

}