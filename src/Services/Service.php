<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 17:03
 */

namespace Services;


use Pimple\Container;

abstract class Service
{

    protected $app;

    protected $defaultRepository;

    public function __construct(Container $app, $repository)
    {
        $this->app=$app;
        $this->defaultRepository=$repository;
    }


}