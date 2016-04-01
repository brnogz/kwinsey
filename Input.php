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
    /**
     * @var Input $instance
     */
    private static $instance;
    
    /**
     * @var array $post
     */
    private $post;

    /**
     * @var array $header
     */
    private $header;

    /**
     * @var array $get
     */
    private $get;

    private function __construct()
    {
        $this->post = $this->escapeArray($_POST);
        $this->header = $this->escapeArray(getallheaders());

        $urlParser = UrlParser::getInstance(Application::getInstance()->getConfiguration());
        $this->get = $this->escapeArray($urlParser->getQuery());
    }

    /**
     * @return Input
     */
    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new Input();
        
        return self::$instance;
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function post(string $key = null)
    {
        if ($key == null)
            return $this->post;
        else
            return isset($this->post[$key]) ? $this->post[$key] : null;
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
}