<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 09.04.2016
 * Time: 18:36
 */

namespace kwinsey\exception;


class ResultNotFound extends \Exception
{

    public function __construct($message="", $code=0, \Exception $previous=null)
    {
        if($message=="")
            $message = "Result is not found";
        parent::__construct($message, $code, $previous);
    }
}