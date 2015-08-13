<?php

namespace Kubernetes\Client\Model;

class LoadBalancerStatus
{
    /**
     * @var LoadBalancerIngress[]
     */
    private $ingresses;

    /**
     * @return LoadBalancerIngress[]
     */
    public function getIngresses()
    {
        return $this->ingresses;
    }
}
