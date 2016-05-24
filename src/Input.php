<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 27.03.2016
 * Time: 12:04
 */

namespace kwinsey;


class Input
{
    use Singleton;

    /** @var array $body */
    private $body;

    /** @var array $header */
    private $header;

    /** @var array $get */
    private $get;

    public function __construct()
    {
        if ($this->requestMethod() == 'POST') {
            $this->body = $this->escapeArray($_POST);
        } else {
            parse_str(file_get_contents("php://input"),$this->body);
        }
        $this->header = $this->escapeArray(getallheaders());

        $urlParser = UrlParser::getInstance(Application::getInstance()->getConfiguration());
        $this->get = $this->escapeArray($urlParser->getQuery());
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function body(string $key = null)
    {
        if (is_null($key))
            return $this->body;
        else
            return isset($this->body[$key]) ? $this->body[$key] : null;
    }

    /**
     * @deprecated 
     * @param string|null $key
     * @return array|mixed|null
     */
    public function post(string $key = null){
        return $this->body($key);
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function header(string $key = null)
    {
        if ($key == null)
            return $this->header;
        else
            return isset($this->header[$key]) ? $this->header[$key] : null;
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function get(string $key = null)
    {
        if ($key == null)
            return $this->get;
        else
            return isset($this->get[$key]) ? $this->get[$key] : null;
    }

    /**
     * @param $source
     * @return array
     */
    private function escapeArray($source)
    {
        if (is_array($source)) {
            foreach ($source as $key => $value)
                $source[$key] = $this->escapeArray($value);
            return $source;
        } else
            return addslashes($source);
    }

    /**
     * @return string
     */
    public function requestMethod():string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}