<?php

namespace Services\Impls;

use Models\User;
use Repositories\Impls\UserRepository;
use Services\Service;


/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 15:49
 */
class UserService extends Service
{
    public function load_user_by_username(string $username = ''){
        /* @var $rep UserRepository */
        $rep = $this->defaultRepository;
        $row = $rep->load_user_by_username($username);

        if (empty($row)) return null;

        $user = new User();
        $user->setId($row["id"]);
        $user->setEmail($row["email"]);
        $user->setAvatar($row["avatar"]);
        $user->setPassword($row["password"]);
        return $user;
    }

    public function saveUser(User $user)
    {
        /* @var $rep UserRepository */
        $rep = $this->defaultRepository;
        if ($user->getAvatar() !== null) {
            $filePath = uniqid() . "_" . $user->getAvatar()->getClientOriginalName();
            if ($user->getAvatar()->move(__DIR__ . '/../../../public/upload', $filePath)) {
                $user->setAvatar($filePath);
            }
        }


        return $rep->saveUser($user);
    }
}