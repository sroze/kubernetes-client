<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

class NetworkPolicyEgressRule
{
    /**
     * @var NetworkPolicyPeer[]
     */
    private $to;

    /**
     * @var NetworkPolicyPort[]
     */
    private $ports;

    /**
     * @param NetworkPolicyPeer[] $to
     * @param NetworkPolicyPort[] $ports
     */
    public function __construct(array $to = [], array $ports = [])
    {
        $this->to = $to;
        $this->ports = $ports;
    }

    /**
     * @return NetworkPolicyPort[]
     */
    public function getPorts(): array
    {
        return $this->ports ?: [];
    }

    /**
     * @return NetworkPolicyPeer[]
     */
    public function getTo(): array
    {
        return $this->to ?: [];
    }
}
