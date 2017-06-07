<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 10:51
 */

namespace kwinsey;


use kwinsey\exception\ResultNotFound;

abstract class Model
{
    use Singleton;

    /** @var  Medoo $db */
    protected $db;
    /** @var array $cache */
    private $cache = [];


    public function __construct()
    {
        $this->db = Medoo::getInstance();
    }

    public function getError()
    {
        return $this->db->error();
    }

    public function getLastQuery(): string
    {
        return $this->db->last();
    }

    public function transaction(callable $actions)
    {
        $this->db->action($actions());
    }

    public function getCached($id, $table)
    {
        if (!isset($this->cache[$id])) {
            $result = $this->db->select($table, '*', ['id' => $id]);

            if (count($result) == 0)
                throw new ResultNotFound();

            $this->cache[$id] = $result[0];
        }

        return $this->cache[$id];
    }

    public function id(){
        return $this->db->id();
    }
}