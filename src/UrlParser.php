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
    use Singleton;

    /** @var Config $config */
    private $config;
    /** @var string[] $segments */
    private $segments;
    /** @var  string $controllerSegment */
    private $controllerSegment;
    /** @var  string $methodSegment */
    private $methodSegment;

    public function __construct($config)
    {
        $this->config = $config;
        $this->parse();
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

            if(count($segments) > 1){
                $this->controllerSegment = implode('/', array_splice($segments, 0, count($segments) - 1));
            } elseif(count($segments) == 1) {
                $this->controllerSegment = array_values($segments)[0];
                $segments = [];
            } else {
                $this->controllerSegment = 'index';
            }

            $this->methodSegment = count($segments) > 0 ? end($segments) : 'index';

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

    /**
     * @return string
     */
    public function getControllerSegment():string
    {
        return $this->controllerSegment;
    }

    /**
     * @return string
     */
    public function getMethodSegment():string
    {
        return $this->methodSegment;
    }


}