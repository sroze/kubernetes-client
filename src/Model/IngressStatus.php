<?php

namespace Kubernetes\Client\Model;

class IngressStatus
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
