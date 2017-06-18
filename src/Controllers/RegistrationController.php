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
        return $app["twig"]->render("registration.twig", array("result"=>""));

    }

    public function registration_post(Application $app, Request $request)
    {
        $errors = [];

//        if ($request->get("password") !== $request->get("conf_password")) $errors[] = "passwords invalid";
//
//        if (!filter_var($request->get("email"), FILTER_VALIDATE_EMAIL)) $errors[] = "email invalid";
//
//        if ($request->files->get("file")->getSize() > 20000) $errors[] = "file error";
//
//        if (empty($errors)) {
            $user = new User();
            $user->setEmail($request->get("email"));
            $user->setPassword($request->get("password"));
            $user->setAvatar($request->files->get("file"));

            $result = $this->userService->saveUser($user);
//        }

        return $app['twig']->render("registration.twig", array(
            "request" => $request,
            "result" => $result
        ));
    }

}