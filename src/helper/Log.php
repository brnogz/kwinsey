<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 09.04.2016
 * Time: 18:36
 */

namespace kwinsey\helper;


class Log
{
    public static function e(\Throwable $e)
    {
        error_log($e->getMessage());
        foreach ($e->getTrace() as $traceLine) {
            $args = str_replace("\n", "", var_export($traceLine['args'], true));
            error_log(@"{$traceLine['file']}({$traceLine['line']}): {$traceLine['class']}{$traceLine['type']}({$args})");
        }
        error_log("---------");
    }
}