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

    /**
     * register function as json producer
     */
    public static function produceJSON()
    {
        static::$produce[static::getCaller()] = Output::JSON;
    }

    /**
     * register function as plain text/html producer
     */
    public static function producePlain()
    {
        static::$produce[static::getCaller()] = Output::PLAIN;
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