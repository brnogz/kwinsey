<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 01.08.2016
 * Time: 21:20
 */

namespace kwinsey\hook;


class Registrar
{
    const PRE = 'pre';
    const POST = 'post';
    private static $hooks = ['pre' => [], 'post' => []];

    public static function newHook(string $when, string $controller, Hook &$hook)
    {
        static::$hooks[$when][$controller][] = &$hook;
    }

    public static function runHooksIfExist(string $when, string $controller)
    {
        if (isset(static::$hooks[$when][$controller]))
            foreach (static::$hooks[$when][$controller] as &$hook) {
                /** @var Hook $hook */
                $hook->run();
            }
    }
}