<?php

namespace Kubernetes\Client\Model;

class ServiceSpecification
{
    const SESSION_AFFINITY_CLIENT_IP = 'ClientIP';
    const SESSION_AFFINITY_NONE = 'None';

    /**
     * @var array
     */
    private $selector;

    /**
     * @var string[]
     */
    private $publicIPs;

    /**
     * @var string
     */
    private $portalIP;

    /**
     * @var ServicePort[]
     */
    private $ports;

    /**
     * @var bool
     */
    private $createExternalLoadBalancer;

    /**
     * @var string
     */
    private $sessionAffinity;

    public function __construct(array $selector, array $ports = [], array $publicIPs = [], $portalIP = null, $createExternalLoadBalancer = false, $sessionAffinity = self::SESSION_AFFINITY_NONE)
    {
        $this->selector = $selector;
        $this->ports = $ports;
        $this->publicIPs = $publicIPs;
        $this->portalIP = $portalIP;
        $this->createExternalLoadBalancer = $createExternalLoadBalancer;
        $this->sessionAffinity = $sessionAffinity;
    }
}
