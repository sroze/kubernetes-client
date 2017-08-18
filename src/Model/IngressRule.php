<?php

namespace Kubernetes\Client\Model;

class IngressRule
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var IngressHttpRule|null
     */
    private $http;

    /**
     * @param string $host
     * @param IngressHttpRule|null $http
     */
    public function __construct(string $host, IngressHttpRule $http = null)
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
