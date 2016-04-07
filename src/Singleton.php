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
    protected static $instance;

    final public static function getInstance(...$args)
    {
        if (!isset(static::$instance))
            if (!isset($args) || is_null($args) || count($args) == 0) {
                static::$instance = new static();
            } else {
                $reflection = new \ReflectionClass(static::class);
                static::$instance = $reflection->newInstanceArgs($args);
            }

        return static::$instance;
    }
}