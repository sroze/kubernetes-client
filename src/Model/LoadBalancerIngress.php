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
