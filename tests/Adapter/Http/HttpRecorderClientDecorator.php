<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Promise\Promise;
use Kubernetes\Client\Adapter\Http\HttpClient;

class HttpRecorderClientDecorator implements HttpClient
{
    /**
     * @var HttpClient
     */
    private $decoratedClient;

    /**
     * @var FileResolver
     */
    private $fileResolver;

    /**
     * @param HttpClient $decoratedClient
     * @param FileResolver $fileResolver
     */
    public function __construct(HttpClient $decoratedClient, FileResolver $fileResolver)
    {
        $this->decoratedClient = $decoratedClient;
        $this->fileResolver = $fileResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        $response = $this->decoratedClient->request($method, $path, $body, $options);

        $filePath = $this->fileResolver->getFilePath($method, $path, $body, $options);
        file_put_contents($filePath, $response);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function asyncRequest($method, $path, $body = null, array $options = [])
    {
        return new Promise(
            function () use ($method, $path, $body, $options) {
                $filePath = $this->fileResolver->request($method, $path, $body, $options);
                $response = $this->decoratedClient->request($method, $path, $body, $options);
                file_put_contents($filePath, $response);
                return $response;
            }
        );
    }
}
