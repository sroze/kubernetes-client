<?php

namespace Kubernetes\Client\Model;

class ServicePort
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $targetPort;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var int|null
     */
    private $nodePort;

    /**
     * @param string   $name
     * @param int      $port
     * @param string   $protocol
     * @param int|null $targetPort
     * @param int|null $nodePort
     */
    public function __construct($name, $port, $protocol, $targetPort = null, $nodePort = null)
    {
        $this->name = $name;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->targetPort = $targetPort ?: $port;
        $this->nodePort = $nodePort;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getTargetPort()
    {
        return $this->targetPort;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return int|null
     */
    public function getNodePort()
    {
        return $this->nodePort;
    }
}
