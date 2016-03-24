<?php

namespace Kubernetes\Client\Model;

class TcpSocketAction
{
    /**
     * @var int
     */
    private $port;

    /**
     * @param int $port
     */
    public function __construct($port)
    {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
}
