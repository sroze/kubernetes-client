<?php

namespace Kubernetes\Client\Model;

class LoadBalancerIngress
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @param string $ip
     * @param string $hostname
     */
    public function __construct($ip, $hostname = null)
    {
        $this->ip = $ip;
        $this->hostname = $hostname;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }
}
