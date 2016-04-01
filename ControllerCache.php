<?php namespace kwinsey;
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 22:08
 */

use kwinsey\config\ControllerMapping;

class ControllerCache
{
    /**
     * @var string $hash
     */
    public $hash;

    /**
     * @var ControllerMapping[] $controllers; 
     */
    public $controllers;
}