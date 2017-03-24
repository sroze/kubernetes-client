<?php

namespace Kubernetes\Client\Model;

class IngressRule
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var IngressHttpRule
     */
    private $http;

    /**
     * @param string $host
     * @param IngressHttpRule $http
     */
    public function __construct(string $host, IngressHttpRule $http)
    {
        $this->host = $host;
        $this->http = $http;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return IngressHttpRule|null
     */
    public function getHttp()
    {
        return $this->http;
    }
}
