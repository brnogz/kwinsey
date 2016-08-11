<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 01.08.2016
 * Time: 21:20
 */

namespace kwinsey\hook;


use kwinsey\helper\Log;

class Registrar
{
    const PRE = 'pre';
    const POST = 'post';
    private static $hooks = ['pre' => [], 'post' => []];

    public static function newHook(string $when, string $controllerClass, string $controllerMethod, string $hook)
    {
        static::$hooks[$when]['\\' . $controllerClass . '\\' . $controllerMethod][] = '\\' . $hook;
    }

    public static function runHooksIfExist(string $when, string $controller, $params = null)
    {
        if (isset(static::$hooks[$when][$controller]))
            foreach (static::$hooks[$when][$controller] as $hook) {
                try {
                    /** @var Hook $hookInstance */
                    $hookInstance = new $hook();
                    $hookInstance->run($params);
                } catch (\Exception $e) {
                    Log::e($e);
                }
            }
    }
}