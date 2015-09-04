<?php

namespace Kubernetes\Client\Model;

class ServiceStatus
{
    /**
     * @var LoadBalancerStatus
     */
    private $loadBalancer;

    /**
     * @param LoadBalancerStatus $loadBalancer
     */
    public function __construct(LoadBalancerStatus $loadBalancer)
    {
        $this->loadBalancer = $loadBalancer;
    }

    /**
     * @return LoadBalancerStatus
     */
    public function getLoadBalancer()
    {
        return $this->loadBalancer;
    }
}
