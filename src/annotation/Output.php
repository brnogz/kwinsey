<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 29.04.2016
 * Time: 19:34
 */

namespace kwinsey\annotation;


class Output extends Annotation
{
    const JSON = "json";
    const PLAIN = "plain";
    private static $produce = [];

    public static function produce(string $type, string $function = null){
        static::$produce[$function ?? static::getCaller()] = $type;
    }

    /**
     * @param string $function
     * @return string return what function produces
     */
    public static function getProduceType(string $function):string
    {
        if(isset(static::$produce[$function]))
            return static::$produce[$function];
        else
            return Output::PLAIN;
    }
    

}