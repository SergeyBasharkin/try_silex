<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 16:51
 */

namespace Repositories\Impls;


use Repositories\Repository;

class WelcomeRepository extends Repository
{
    public function index(){
        $queryBuilder = $this->connect->createQueryBuilder();
        $row = $queryBuilder->select('*')->from('test');
        $result = $row->execute()->fetch();

        return $result;
    }

    public function getBy($column)
    {

    }
}