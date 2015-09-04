<?php

namespace Kubernetes\Client\Model;

class LoadBalancerStatus
{
    /**
     * @var LoadBalancerIngress[]
     */
    private $ingresses;

    /**
     * @param LoadBalancerIngress[] $ingresses
     */
    public function __construct(array $ingresses)
    {
        $this->ingresses = $ingresses;
    }

    /**
     * @return LoadBalancerIngress[]
     */
    public function getIngresses()
    {
        return $this->ingresses;
    }
}
