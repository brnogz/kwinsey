<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 17.04.2016
 * Time: 13:26
 */

namespace kwinsey;


class Medoo extends \medoo
{
    use Singleton;

    public function __construct()
    {
        $config = Application::getInstance()->getConfiguration()->database;

        $dbConfig = array();
        foreach ($config as $key => $val)
            if ($key != 'option')
                $dbConfig[$key] = $val;
            else
                foreach ($val as $k => $v)
                    $dbConfig[$key][constant('\PDO::' . $k)] = constant('\PDO::' . $v);
        
        parent::__construct($config);
    }
}