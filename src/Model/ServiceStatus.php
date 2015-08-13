<?php

namespace Kubernetes\Client\Model;

class ServiceStatus
{
    /**
     * @var LoadBalancerStatus
     */
    private $loadBalancer;

    /**
     * @return LoadBalancerStatus
     */
    public function getLoadBalancer()
    {
        return $this->loadBalancer;
    }
}
