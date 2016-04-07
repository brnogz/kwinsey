<?php namespace kwinsey;

use kwinsey\config\Config;

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 15:38
 */
class UrlParser
{
    /**
     * @var UrlParser $instance
     */
    private static $instance;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var string[] $segments ;
     */
    private $segments;

    private function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $config
     * @return UrlParser
     */
    public static function getInstance($config) : UrlParser
    {
        if (self::$instance == null)
            self::$instance = new UrlParser($config);

        return self::$instance;
    }

    /**
     * @return array
     */
    public function getQuery():array
    {
        $qMarkPos = strpos($_SERVER['QUERY_STRING'], '?');
        if ($qMarkPos !== false) {
            $queryStr = substr($_SERVER['QUERY_STRING'], $qMarkPos, strlen($_SERVER['QUERY_STRING']));
            return parse_str($queryStr);
        } else {
            return array();
        }
    }

    /**
     * @return string
     */
    public function getPath():string
    {
        $qMarkPos = strpos($_SERVER['QUERY_STRING'], '?');
        if ($qMarkPos !== false)
            return substr($_SERVER['QUERY_STRING'], 0, $qMarkPos);
        else
            return $_SERVER['QUERY_STRING'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function parse() : string
    {
        if ($this->config->general->sef_enabled == 1) {
            $path = $this->getPath();

            $segments = explode('/', $path);
            foreach ($segments as $key => $val)
                if (empty($val))
                    unset($segments[$key]);

            $this->segments = array_values($segments);

            return $path;
        } else {
            // TODO it has to work with regular url 
            throw new \Exception("disabled sef not implemented");
        }
    }

    public function getSegments()
    {
        return $this->segments;
    }
}