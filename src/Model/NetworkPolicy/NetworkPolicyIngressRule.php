<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

class NetworkPolicyIngressRule
{
    /**
     * @var NetworkPolicyPeer[]
     */
    private $from;

    /**
     * @var NetworkPolicyPort[]
     */
    private $ports;

    /**
     * @param NetworkPolicyPeer[] $from
     * @param NetworkPolicyPort[] $ports
     */
    public function __construct(array $from = [], array $ports = [])
    {
        $this->from = $from;
        $this->ports = $ports;
    }

    /**
     * @return NetworkPolicyPeer[]
     */
    public function getFrom(): array
    {
        return $this->from ?: [];
    }

    /**
     * @return NetworkPolicyPort[]
     */
    public function getPorts(): array
    {
        return $this->ports ?: [];
    }
}
