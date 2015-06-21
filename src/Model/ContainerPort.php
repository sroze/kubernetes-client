<?php

namespace Kubernetes\Client\Model;

class ContainerPort
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $containerPort;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @param string $name
     * @param int    $containerPort
     * @param string $protocol
     */
    public function __construct($name, $containerPort, $protocol)
    {
        $this->name = $name;
        $this->containerPort = $containerPort;
        $this->protocol = $protocol;
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
    public function getContainerPort()
    {
        return $this->containerPort;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
