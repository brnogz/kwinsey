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
    /**
     * @var Singleton $instance
     */
    protected static $instance;

    /**
     * @param array ...$args
     * @return Singleton
     */
    final public static function getInstance(...$args): Singleton
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