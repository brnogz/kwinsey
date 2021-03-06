<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 17.04.2016
 * Time: 13:26
 */

namespace kwinsey;


class Medoo
{
    private static $instance;

    public static function getInstance() : \Medoo\Medoo
    {
        if (static::$instance == null) {
            $config = Application::getInstance()->getConfiguration()->database;

            $dbConfig = [];
            foreach ($config as $key => $val)
                if ($key != 'option')
                    $dbConfig[$key] = $val;
                else
                    foreach ($val as $k => $v)
                        $dbConfig[$key] = [constant('\PDO::' . $k) => constant('\PDO::' . $v)];

            static::$instance = new \Medoo\Medoo($dbConfig);
        }
        return static::$instance;
    }
}