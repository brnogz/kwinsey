<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 13:46
 */

namespace kwinsey;


class Output
{
    public static function write(Response $response = null)
    {
        if (is_null($response)) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->setData("Response cannot be null");
        }
        
        http_response_code($response->getStatusCode());
        echo json_encode($response->getData());
    }
}