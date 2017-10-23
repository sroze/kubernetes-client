<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class FileHttpClient implements HttpClient
{
    /**
     * @var FileResolver
     */
    private $fileResolver;

    /**
     * @param FileResolver $fileResolver
     */
    public function __construct(FileResolver $fileResolver)
    {
        $this->fileResolver = $fileResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        $filePath = $this->fileResolver->getFilePath($method, $path, $body, $options);
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf(
                'The HTTP fixture file "%s" do not exists',
                $filePath
            ));
        }

        return file_get_contents($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncRequest($method, $path, $body = null, array $options = [])
    {
        $filePath = $this->fileResolver->getFilePath($method, $path, $body, $options);
        /** @var PromiseInterface $promise */
        $promise = new Promise(
            function () use (&$promise, $filePath) {
                if (!file_exists($filePath)) {
                    throw new \RuntimeException(sprintf(
                        'The HTTP fixture file "%s" do not exists',
                        $filePath
                    ));
                }

                $promise->resolve(file_get_contents($filePath));
            }
        );
        return $promise;
    }
}
