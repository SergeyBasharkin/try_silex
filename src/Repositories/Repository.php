<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 16:01
 */

namespace Repositories;


use Doctrine\DBAL\Connection;

abstract class Repository
{
    /**
     * @var Connection
     */
    protected $connect;

    public function __construct(Connection $connection)
    {
        $this->connect=$connection;
    }

    abstract public function index();

    abstract public function getBy($column);


}