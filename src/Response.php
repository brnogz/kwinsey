<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 13:41
 */

namespace kwinsey;


class Response
{
    /**
     * @var int $statusCode
     */
    private $statusCode = 200;

    /**
     * @var mixed $data
     */
    private $data;

    public function __construct($data =null, $status = 200)
    {
        $this->data = $data;
        $this->statusCode = $status;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode){
        $this->statusCode = $statusCode;
    }

    /**
     * @param mixed $data
     */
    public function setData($data){
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    
}