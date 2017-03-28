<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Promise\PromiseInterface;

interface HttpClient
{
    /**
     * @param string $method
     * @param string $path
     * @param string $body
     * @param array  $options
     *
     * @return string
     */
    public function request($method, $path, $body = null, array $options = []);

    /**
     * @param $method
     * @param $path
     * @param null $body
     * @param array $options
     * @return PromiseInterface
     */
    public function asyncRequest($method, $path, $body = null, array $options = []);
}
