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
    use Singleton;

    const CONTROLLER_MAP_CACHE = '/cache/controller_map_cache';

    /**  @var Config $config */
    private $config;

    /**  @var ControllerCache $controllerMapCache */
    private $controllerMapCache;

    /** @var string $appPath */
    private $appPath;

    /** @var  string $controllerClass */
    private $controllerClass;

    /** @var  string $controllerClassPath */
    private $controllerClassPath;

    /**
     * ControllerInitializer constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->appPath = Application::getInstance()->getAppPath();
        $this->indexControllers();
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
    public function getController(string $path): Controller
    {
        $controllerClassPath = 'index';
        foreach (array_keys($this->controllerMapCache->controllers) as $controllerPath) {
            if (strpos(trim($path,'/'), $controllerPath) === 0) {
                $controllerClassPath = $controllerPath;
                break;
            }
        }

        $this->controllerClassPath = $controllerClassPath;
        $this->controllerClass = $this->controllerMapCache->controllers[$controllerClassPath];

        try {
            return (new $this->controllerClass());
        } catch (\Error $e) {
            throw $e;
        }
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getControllerClassPath(): string
    {
        return $this->controllerClassPath;
    }




}