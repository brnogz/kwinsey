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
    /** @var  string $path */
    private $path;
    /** @var  string[] $params */
    private $params;

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
        if (is_null($this->path)) {
            $qMarkPos = strpos($_SERVER['QUERY_STRING'], '?');
            if ($qMarkPos !== false)
                $this->path = substr($_SERVER['QUERY_STRING'], 0, $qMarkPos);
            else
                $this->path = $_SERVER['QUERY_STRING'];
        }
        return $this->path;
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

    /**
     * @return string
     */
    public function getControllerSegment():string
    {
        return $this->controllerSegment;
    }

    /**
     * @param string|null $controllerClassPath
     * @return string
     */
    public function getMethodSegment(string $controllerClassPath = null):string
    {
        if (is_null($this->methodSegment)) {
            $controllerPathSegments = explode('/', $controllerClassPath);
            foreach ($controllerPathSegments as &$segment)
                if (empty($segment))
                    unset($segment);
            $controllerPathSegments = array_values($controllerPathSegments);
            $methodIndex = count($controllerPathSegments);

            if(isset($this->segments[$methodIndex]))
                $this->methodSegment = $this->segments[$methodIndex];
            else
                $this->methodSegment = 'index';

            if(count($this->segments)>$methodIndex+1)
                $this->params = array_slice($this->segments,$methodIndex+1,count($this->segments) - ($methodIndex + 1));
            else
                $this->params = [];
        }
        return $this->methodSegment;
    }


    public function getParams()
    {
        return $this->params;
    }

}