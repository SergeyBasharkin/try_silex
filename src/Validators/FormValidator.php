<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 19:06
 */

namespace Validators;


use Models\User;
use Symfony\Component\HttpFoundation\Request;

class FormValidator
{

    public function validateRegistrationForm(Request $request)
    {
        $errors = [];
        dump($request->get("password"));
        dump($request->get("confirm_password"));
        dump($request->files->get("file")->getSize());
        if ($request->get("password") !== $request->get("confirm_password")) {
            $errors[] = "passwords invalid";
        }

        if (!filter_var($request->get("email"), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "email invalid";
        }

        if ($request->files->get("file") != null && $request->files->get("file")->getSize() > 20000000) {
            $errors[] = "file error";
        }

        return $errors;
    }

    public function validateLoginForm(User $user, Request $request)
    {
        $errors = [];
        if ($user === null) {
            $errors[] = "user not found";
        } elseif ($request->get('login') !== $user->getEmail() && !password_verify($request->getPassword(), $user->getPassword())) {
            $errors[] = "bad credentials";
        }
        return $errors;
    }

    public function validatePostForm($request)
    {
        $errors = [];

        return $errors;
    }
}