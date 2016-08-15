<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 10.08.2016
 * Time: 22:02
 */
namespace kwinsey;

class DataStore
{
    use Singleton;

    private $store = [];

    public function put(string $name, $value)
    {
        $this->store[$name] = $value;
    }

    public function get(string $name)
    {
        return isset($this->store[$name]) ? $this->store[$name] : null;
    }

    public function del(string $name)
    {
        unset($this->store[$name]);
    }
}