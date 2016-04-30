<?php namespace kwinsey;

use kwinsey\config\Config;
use kwinsey\exception\ApplicationNotCreatedException;
use kwinsey\exception\FileNotFoundException;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\RefResolver;
use JsonSchema\Validator;
use kwinsey\helper\Log;

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 24.03.2016
 * Time: 21:56
 */
class Application
{
    use Singleton;

    /** @var Config $configuration */
    private $configuration;

    /** @var string $appPath */
    private $appPath;

    /**
     * Application constructor.
     * @param string|null $configFilePath
     * @param string $path
     * @throws ApplicationNotCreatedException
     * @throws FileNotFoundException
     */
    public function __construct(string $configFilePath = null, string $path = __DIR__)
    {
        if ($configFilePath == null && static::$instance == null)
            throw new ApplicationNotCreatedException();

        if (!file_exists($configFilePath))
            throw new FileNotFoundException();

        $this->appPath = $path;

        $retriever = new UriRetriever;

        $schema = $retriever->retrieve('file://' . __DIR__ . '/config/config.json');
        $this->configuration = json_decode(file_get_contents($configFilePath));


        $refResolver = new RefResolver($retriever);
        $refResolver->resolve($schema, 'file://' . __DIR__);

        $validator = new Validator();
        $validator->check($this->configuration, $schema);

        if (!$validator->isValid()) {
            error_log("JSON does not validate. Violations:");
            foreach ($validator->getErrors() as $error) {
                error_log(sprintf("[%s] %s", $error['property'], $error['message']));
            }

            die();
        }
    }

    /**
     * @return Config
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function getAppPath():string
    {
        return $this->appPath;
    }

    /**
     * @throws \Exception
     * @throws exception\ControllerNotDefinedException
     */
    public function run()
    {
        /** @var UrlParser $urlParser */
        $urlParser = UrlParser::getInstance($this->configuration);

        /**  @var ControllerInitializer $controllerInitializer */
        $controllerInitializer = ControllerInitializer::getInstance($this->configuration);
        
        try {
            $controller = $controllerInitializer->getController($urlParser->getControllerSegment());
            $response = $controller->{$urlParser->getMethodSegment()}();
        } catch (\Throwable $e) {
            Log::e($e);

            $response = new Response();
            $response->setData($e->getMessage());
            $response->setStatusCode(500);
        }

        Output::write($response, $urlParser->getMethodSegment());
    }
}