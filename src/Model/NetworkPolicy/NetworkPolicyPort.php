<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

class NetworkPolicyPort
{
    /**
     * @var int
     */
    private $port;

    /**
     * @var string|null
     */
    private $protocol;

    public function __construct(int $port, string $protocol = null)
    {
        $this->port = $port;
        $this->protocol = $protocol;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
