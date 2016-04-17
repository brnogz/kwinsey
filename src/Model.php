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

    /** @var  Medoo $db */
    protected $db;


    public function __construct()
    {
        $this->db = Medoo::getInstance();
    }
}