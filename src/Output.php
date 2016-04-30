<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 13:46
 */

namespace kwinsey;

use kwinsey\annotation\Output as O;

class Output
{

    /**
     * @param Response|null $response
     */
    public static function writeJSON(Response $response = null)
    {
        $response = static::responseCheck($response);
        
        http_response_code($response->getStatusCode());
        
        echo json_encode($response->getData());
    }

    /**
     * @param Response|null $response
     */
    public static function writePlain(Response $response = null)
    {
        $response = static::responseCheck($response);
        
        http_response_code($response->getStatusCode());
        
        if (is_string($response))
            echo $response;
        else {
            echo "<pre>";
            var_dump($response);
        }
    }

    /**
     * @param Response|null $response
     * @param string|null $functionName
     */
    public static function write(Response $response = null, string $functionName = null)
    {

        switch (O::getProduceType($functionName)) {
            case O::PLAIN:
                static::writePlain();
                break;
            case O::JSON:
                static::writeJSON();
                break;
        }
    }

    private static function responseCheck(Response $response = null):Response
    {
        if (is_null($response)) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->setData("Response cannot be null");
        }

        return $response;
    }

}