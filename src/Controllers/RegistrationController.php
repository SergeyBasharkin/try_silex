<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 12:20
 */

namespace Controllers;


use Constraints\NumberContains;
use Models\User;
use Services\Impls\UserService;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
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
        $form = $this->buildRegistrationForm($app);
        return $app["twig"]->render("registration.twig", array("result" => "", "form" => $form->createView()));

    }

    public function registration_post(Application $app, Request $request)
    {


//        /** @var FormValidator $validator */
//        $validator = $app["validator"];
//        $errors = $validator->validateRegistrationForm($request);
        $form = $this->buildRegistrationForm($app);

        $form->handleRequest($request);
        if ($form->isValid()){
            dump($form->getData());
        }else{
            dump('notValid');
        }
//
//        $result = false;
//        if ($this->userService->load_user_by_username($request->get("email")) !== null) {
//            $errors[] = "already exists";
//        }
//        if (empty($errors)) {
//
//            $user = new User();
//            $user->setEmail($app->escape($request->get("email")));
//            $user->setPassword(password_hash($request->get("password"), PASSWORD_BCRYPT));
//            $user->setAvatar($request->files->get("file"));
//
//
//            $result = $this->userService->saveUser($user);
//
//            return $app->redirect("/login");
//        }
        return $app['twig']->render("registration.twig", array(
//            "errors" => $errors,
//            "result" => $result
            "form" => $form->createView()
        ));
    }

    private function buildRegistrationForm($app)
    {
        return $app['form.factory']->createBuilder()
            ->add('email', EmailType::class, array(
                'constraints' => new NotBlank()
            ))
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('name', TextType::class, array(
                'constraints' => new NumberContains()
            ))->getForm();
    }

}