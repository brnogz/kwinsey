<?php namespace kwinsey\helper;
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 21:57
 */

class File
{
    /**
     * @param string $name
     * @param $data
     * @return bool
     */
    public static function writeToFile(string $name, $data):bool
    {
        return \file_put_contents($name, \serialize($data)) !== false;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function readFromFile(string $name)
    {
        return \unserialize(\file_get_contents($name));
    }

    public static function concatPaths(string $from, string $to)
    {
        $segments = explode(DIRECTORY_SEPARATOR, $to);
        foreach ($segments as $segment) {
            if ($segment == '..') {
                $from = dirname($from);
            } else if ($segment != '.') {
                $from .= DIRECTORY_SEPARATOR . $segment;
            }
        }

        if (substr($to, -1) == DIRECTORY_SEPARATOR)
            $from .= DIRECTORY_SEPARATOR;

        return $from;
    }
}