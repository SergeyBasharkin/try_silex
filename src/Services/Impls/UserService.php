<?php

namespace Services\Impls;

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
        return $rep->load_user_by_username($username);
    }
}