<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 18:11
 */

namespace Repositories\Impls;


use Models\User;
use Repositories\Repository;

class UserRepository extends Repository
{

    public function index()
    {

    }

    public function load_user_by_username($username)
    {
        $queryBuilder = $this->connect->createQueryBuilder();

        $stm = $queryBuilder
            ->select('*')
            ->from("users")
            ->where('email = ?')
            ->setParameter(0, $username);

        return $stm->execute()->fetch();
    }

    public function getBy($column)
    {
        // TODO: Implement getBy() method.
    }

    public function saveUser(User $user)
    {
        $queryBuilder = $this->connect->createQueryBuilder();

        $stm = $queryBuilder
            ->insert('users')
            ->values(
                array(
                    'email' => '?',
                    'password' => '?',
                    'avatar' => '?'
                )
            )
            ->setParameter(0, $user->getEmail())
            ->setParameter(1, $user->getPassword())
            ->setParameter(2, $user->getAvatar());
        return $result = $stm->execute();
    }
}