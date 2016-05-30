<?php

namespace Kubernetes\Client\Model;

class IngressBackend
{
    /**
     * @var string
     */
    private $serviceName;

    /**
     * @var int
     */
    private $servicePort;

    /**
     * @param string $serviceName
     * @param int    $servicePort
     */
    public function __construct($serviceName, $servicePort)
    {
        $this->serviceName = $serviceName;
        $this->servicePort = $servicePort;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return int
     */
    public function getServicePort()
    {
        return $this->servicePort;
    }
}
