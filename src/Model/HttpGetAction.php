<?php

namespace Kubernetes\Client\Model;

class HttpGetAction
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @param string $path
     * @param int    $port
     * @param string $host
     * @param string $scheme
     */
    public function __construct($path, $port, $host, $scheme)
    {
        $this->path = $path;
        $this->port = $port;
        $this->host = $host;
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }
}
