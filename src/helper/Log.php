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
        error_log("========");
        error_log($e->getMessage());
        foreach ($e->getTrace() as $traceFile) {
            foreach ($traceFile as $what => $traceLine)
                error_log("{$what} : " . json_encode($traceLine));
            error_log("-------");
        }
        error_log("========");
    }
}