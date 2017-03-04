<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 09.04.2016
 * Time: 18:36
 */

namespace kwinsey\helper;


use kwinsey\Application;
use kwinsey\config\Config;

class Log
{
    /**
     * @param $data
     */
    public static function d($data)
    {
        static::log(var_export($data, true));
        static::log("---------");
    }

    private static function log($str)
    {
        /** @var Config $config */
        $config = Application::getInstance()->getConfiguration();

        error_log('[' . date('Y-m-d H:i:s') . ']' . $str . PHP_EOL, 3, $config->general->log_loc);
    }

    /**
     * @param \Throwable $e
     */
    public static function e(\Throwable $e)
    {
        static::log($e->getMessage());
        foreach ($e->getTrace() as $traceLine) {
            $traceLine['args'] = !isset($traceLine['args']) || empty($traceLine['args']) ? null : $traceLine['args'];
            $traceLine['file'] = !isset($traceLine['file']) || empty($traceLine['file']) ? null : $traceLine['file'];
            $traceLine['line'] = !isset($traceLine['line']) || empty($traceLine['line']) ? null : $traceLine['line'];
            $traceLine['class'] = !isset($traceLine['class']) || empty($traceLine['class']) ? null : $traceLine['class'];
            $traceLine['type'] = !isset($traceLine['type']) || empty($traceLine['type']) ? null : $traceLine['type'];

            $args = str_replace("\n", "", var_export($traceLine['args'], true));
            static::log("{$traceLine['file']}({$traceLine['line']}): {$traceLine['class']}{$traceLine['type']}({$args})");
        }
        static::log("---------");
    }
}