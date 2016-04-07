<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 10:51
 */

namespace kwinsey;


abstract class Model
{
    use Singleton;
    
    /**
     * @var \medoo $db
     */
    protected $db;


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

        $this->db = new \medoo($dbConfig);
    }
}