<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 17.06.17
 * Time: 16:57
 */

namespace Services\Impls;


use Services\Service;

class WelcomeService extends Service
{

    public function welcome()
    {
        return $this->defaultRepository->index()["name"];
    }

}