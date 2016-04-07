<?php namespace kwinsey\config;
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 16:03
 */


class Config
{
    /**
     * @var GeneralSettings $general
     */
    public $general;
    /**
     * @var ControllerMapping[] $controllers
     */
    public $controllers;

    /**
     * @var DatabaseSettings $database
     */
    public $database;
    
}

class GeneralSettings
{
    /**
     * @var boolean $sef_enabled
     */
    public $sef_enabled;
}

class ControllerMapping
{
    /**
     * @var string $path
     */
    public $path;

    /**
     * @var string $class
     */
    public $class;
}

class DatabaseSettings
{
    /**
     * @var string $database_type
     */
    public $database_type;

    /**
     * @var string $database_name
     */
    public $database_name;

    /**
     * @var string $server
     */
    public $server;

    /**
     * @var string $username
     */
    public $username;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var string $charset
     */
    public $charset;

    /**
     * @var string $portng
     */
    public $port;

    /**
     * @var string $prefix
     */
    public $prefix;

    /**
     * @var array $option
     */
    public $option;

}