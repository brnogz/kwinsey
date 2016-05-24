<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 07.04.2016
 * Time: 23:45
 */

namespace kwinsey;


trait Singleton
{

    final public static function getInstance(...$args)
    {
        static $instance = null;
        $currentClass = static::class;

        if (is_null($instance))
            if (!isset($args) || is_null($args) || count($args) == 0) {
                $instance = new $currentClass();
            } else {
                $reflection = new \ReflectionClass($currentClass);
                $instance = $reflection->newInstanceArgs($args);
            }

        return $instance;
    }
}