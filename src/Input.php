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
        preg_match_all('(([a-zA-Z0-9_]+([\[][a-zA-Z0-9_]*[\]])*)(=([a-zA-Z0-9\s%.,;:]*))?)', $input, $matches, PREG_SET_ORDER);

        $tBody = [];
        foreach ($matches as $match) {
            $indexes = [];
            preg_match_all('([\[][a-zA-Z0-9_]*[\]])', $match[1], $indexes);

            if (($end = strpos($match[1], '[')) !== false) {
                $key = substr($match[1], 0, $end);
            } else {
                $key = $match[1];
            }

            $temp = $match[4];
            $curIndexes = $indexes[0];
            if (count($curIndexes) > 1 || !empty($curIndexes)) {
                for ($i = count($curIndexes) - 1; $i >= 0; $i--) {
                    $index = str_replace(['[', ']'], '', $curIndexes[$i]);
                    if (!empty($index)) {
                        $t2 = [$index => $temp];
                    } else {
                        $t2 = [$temp];
                    }
                    $temp = $t2;
                }
            }
            if (isset($tBody[$key])) {
                $tBody[$key] = array_merge_recursive($tBody[$key], $temp);
            } else {
                $tBody[$key] = $temp;
            }
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