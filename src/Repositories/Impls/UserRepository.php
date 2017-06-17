<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 18:11
 */

namespace Repositories\Impls;


use Repositories\Repository;

class UserRepository extends Repository
{

    public function index()
    {

    }

    public function load_user_by_username($username){
        $queryBuilder = $this->connect->createQueryBuilder();

        $stm = $queryBuilder
            ->select('*')
            ->from("users")
            ->where('name = ?')
            ->setParameter(0, $username);

        return $stm->execute()->fetch();
    }

    public function getBy($column)
    {
        // TODO: Implement getBy() method.
    }
}