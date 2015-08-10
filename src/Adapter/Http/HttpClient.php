<?php

namespace Kubernetes\Client\Adapter\Http;

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
}
