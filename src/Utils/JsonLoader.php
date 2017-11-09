<?php

/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 19.10.17
 * Time: 15:58
 */
namespace Utils;

class JsonLoader
{
    public function load($path){
        $file = file_get_contents($path);
        return json_decode($file);
    }
}