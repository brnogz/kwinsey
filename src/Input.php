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
        $this->parseBody(file_get_contents("php://input"));
        $this->header = $this->escapeArray(getallheaders());

        $urlParser = UrlParser::getInstance(Application::getInstance()->getConfiguration());
        $this->get = $this->escapeArray($urlParser->getQuery());
    }

    /**
     * @param string $input
     * @return array
     */
    private function parseBody(string $input):array
    {
        $matches = null;
        $input = urldecode($input);

        preg_match_all('/(([\\pL0-9_]+([\[][\\pL0-9_]*[\]])*)(=([\\pL0-9-_\s%.,;:@#!^\/+]*))?)/u', $input, $matches, PREG_SET_ORDER);

        $tBody = [];
        foreach ($matches as $match) {
            $indexes = [];
            preg_match_all('/([\[][\\pL0-9_]*[\]])/u', $match[2], $indexes);
            if (($end = strpos($match[2], '[')) !== false) {
                $key = substr($match[2], 0, $end);
            } else {
                $key = $match[2];
            }

            if (!isset($tBody[$key]))
                $tBody[$key] = [];

            $temp = &$tBody[$key];
            $curlIndexes = $indexes[0];
            if (count($curlIndexes) > 0) {
                foreach ($curlIndexes as $index) {
                    $index = str_replace(['[', ']'], '', $index);

                    if (!empty($index)) {
                        if (is_int($index)) {
                            $nextIndex = count($temp);
                            $temp[$nextIndex] = [];
                            $temp = &$temp[$nextIndex];
                        } else {
                            if(!isset($temp[$index]))
                                $temp[$index] = [];
                            $temp = &$temp[$index];
                        }
                    } else {
                        $nextIndex = count($temp);
                        $temp[$nextIndex] = [];
                        $temp = &$temp[$nextIndex];
                    }
                }
            }
            $temp = $match[5];

        }

        $this->body = $tBody;

        return $tBody;
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