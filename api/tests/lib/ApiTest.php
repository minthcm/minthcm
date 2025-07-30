<?php

use PHPUnit\Framework\TestCase;
use MintHCM\Api\ApiManager;
use MintHCM\Api\Config\AppConfig;
use MintHCM\Api\Containers\Doctrine\DoctrineContainerBuilder;
use MintHCM\Utils\CustomLoader;
use Slim\Factory\AppFactory;
use MintHCM\tests\lib\Middlewares\Parsers\AppJsonBodyParserMiddleware;

abstract class ApiTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        global $app;
        if (!$app) {
            $config = CustomLoader::getObject(AppConfig::class);
            $doctrineContainerBuilder = new DoctrineContainerBuilder();
            $doctrineContainer = $doctrineContainerBuilder->build();
            $app = AppFactory::createFromContainer($doctrineContainer);
            //             require_once 'tests/lib/Middlewares/Parsers/AppJsonBodyParserMiddleware.php';
            //             $x = new AppJsonBodyParserMiddleware();
            // $app->add(AppJsonBodyParserMiddleware::class);
            $manager = ApiManager::getInstance();
            $manager->execute();
            $app->setBasePath($config::getBasePath());
        }
        $this->app = $app;
    }

    protected function tearDown(): void
    {
        // Clean up after each test if necessary
    }

    protected function getApp()
    {
        return $this->app;
    }

    protected function request($method, $uri, $data = [], $headers = [])
    {
        $request = (new \Slim\Psr7\Factory\ServerRequestFactory())
            ->createServerRequest($method, $uri)
            ->withHeader('Content-Type', 'application/json');

        if (!empty($data)) {
            $streamFactory = new \Slim\Psr7\Factory\StreamFactory();
            $bodyStream = $streamFactory->createStream(json_encode($data));
            $request = $request->withBody($bodyStream);
        }

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $this->app->handle($request);
    }

    protected function getBody($response)
    {
        $body = $response->getBody();
        if ($body instanceof \Slim\Psr7\Stream) {
            $data = json_decode($body->__toString(), true);
        } else {
            $data = json_decode((string)$body, true);
        }
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('JSON parse error: ' . json_last_error_msg());
        }
        return $data;
    }

    public function assertArrayIsSortedAscending($array = [], $msg = "Array is not sorted in ascending order.")
    {
        if (empty($array)) {
            $this->markTestSkipped("Array is empty, cannot test sorting.");
        }

        $sorted = $array;
        sort($sorted);

        $this->assertSame($sorted, $array, $msg);
    }

    public function assertArrayIsSortedDescending($array = [], $msg = "Array is not sorted in descending order.")
    {
        if (empty($array)) {
            $this->markTestSkipped("Array is empty, cannot test sorting.");
        }

        $sorted = $array;
        rsort($sorted);

        $this->assertSame($sorted, $array, $msg);
    }
}
