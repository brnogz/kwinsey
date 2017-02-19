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
        return $this->db->last_query();
    }

    public function transaction(callable $actions)
    {
        $this->db->action($actions());
    }

    public function getCached($id, $table)
    {
        if (!isset($this->cache[$id]))
            $this->cache[$id] = @$this->db->select($table, '*', ['id' => $id])[0];

        return $this->cache[$id];
    }
}