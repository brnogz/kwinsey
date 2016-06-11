<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 11.06.2016
 * Time: 17:40
 */

namespace kwinsey;


class ExceptionCatcher
{
    private $callBackMap = [];

    public function register(string $type, callable $callback)
    {
        $this->callBackMap[$type] = $callback;
    }

    public function catchUp(\Throwable $e)
    {
        \Throwable::class;
        $exceptionReflection = new \ReflectionClass($e);
        $caught = false;
        if (isset($this->callBackMap[$exceptionReflection->getName()]))
            $caught = $this->callBackMap[$exceptionReflection->getName()];

        return $caught;
    }
}