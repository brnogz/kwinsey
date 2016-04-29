<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 29.04.2016
 * Time: 19:40
 */

namespace kwinsey\annotation;


use kwinsey\helper\Log;

abstract class Annotation
{
    /**
     * @return string function that called annotation
     */
    protected static function getCaller():string
    {
        $trace = debug_backtrace();
        $function = null;

        try {
            $function = $trace[2]['function'];
        } catch (\Throwable $e) {
            $function = "";
            Log::e($e);
        }

        return $function;
    }
}