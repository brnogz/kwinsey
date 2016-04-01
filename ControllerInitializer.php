<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 21:53
 */

namespace kwinsey;


use kwinsey\config\Config;
use kwinsey\exception\ControllerNotDefinedException;
use kwinsey\helper\File;

class ControllerInitializer
{
    const CONTROLLER_MAP_CACHE = '/cache/controller_map_cache';

    /**
     * @var ControllerInitializer $instance
     */
    private static $instance;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var ControllerCache $controllerMapCache
     */
    private $controllerMapCache;

    /**
     * @var string $appPath
     */
    private $appPath;

    /**
     * ControllerInitializer constructor.
     * @param $config
     */
    private function __construct($config)
    {
        $this->config = $config;
        $this->appPath = Application::getInstance()->getAppPath();
    }

    /**
     * @param $config
     * @return ControllerInitializer
     */
    public static function getInstance($config) : ControllerInitializer
    {
        if (self::$instance == null)
            self::$instance = new ControllerInitializer($config);

        return self::$instance;
    }


    public function indexControllers()
    {
        $isCached = false;
        if (\file_exists(self::CONTROLLER_MAP_CACHE)) {
            $this->controllerMapCache = File::readFromFile($this->appPath . self::CONTROLLER_MAP_CACHE);
            if ($this->controllerMapCache->hash == \sha1(\serialize($this->config->controllers))) {
                $isCached = true;
            }
        }
        if (!$isCached) {
            $this->controllerMapCache = new ControllerCache();
            $this->controllerMapCache->hash = \sha1(\serialize($this->config->controllers));
            foreach ($this->config->controllers as $controller) {
                $this->controllerMapCache->controllers[$controller->path] = $controller->class;
            }
            File::writeToFile($this->appPath . self::CONTROLLER_MAP_CACHE, $this->controllerMapCache);
        }
    }

    /**
     * @param $path
     * @return Controller
     * @throws ControllerNotDefinedException
     * @throws \Error
     */
    public function getController($path): Controller
    {
        $controller = null;
        if (isset($this->controllerMapCache->controllers[$path])) {
            $controller = $this->controllerMapCache->controllers[$path];
        } else if (isset($this->controllerMapCache->controllers['index'])) {
            $controller = $this->controllerMapCache->controllers['index'];
        } else {
            throw new ControllerNotDefinedException("Controller is not defined for {$path}");
        }

        try {
            return new $controller();
        } catch (\Error $e){
            throw $e;
        }
    }
}