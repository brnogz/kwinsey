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
    public static function write(Response $response)
    {
        http_response_code($response->getStatusCode());
        echo json_encode($response->getData());
    }
}