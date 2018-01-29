<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

use Kubernetes\Client\Model\LabelSelector;

class NetworkPolicySpec
{
    /**
     * @var NetworkPolicyEgressRule[]
     */
    private $egress;

    /**
     * @var NetworkPolicyIngressRule[]
     */
    private $ingress;

    /**
     * @var LabelSelector|null
     */
    private $podSelector;

    /**
     * @var string[]
     */
    private $policyTypes;

    /**
     * @param NetworkPolicyEgressRule[] $egress
     * @param NetworkPolicyIngressRule[] $ingress
     * @param LabelSelector $podSelector
     * @param string[] $policyTypes
     */
    public function __construct(array $egress = [], array $ingress = [], LabelSelector $podSelector = null, array $policyTypes = [])
    {
        $this->egress = $egress;
        $this->ingress = $ingress;
        $this->podSelector = $podSelector;
        $this->policyTypes = $policyTypes;
    }

    /**
     * @return NetworkPolicyEgressRule[]
     */
    public function getEgress(): array
    {
        return $this->egress ?: [];
    }

    /**
     * @return NetworkPolicyIngressRule[]
     */
    public function getIngress(): array
    {
        return $this->ingress ?: [];
    }

    /**
     * @return LabelSelector|null
     */
    public function getPodSelector()
    {
        return $this->podSelector;
    }

    /**
     * @return string[]
     */
    public function getPolicyTypes(): array
    {
        return $this->policyTypes ?: [];
    }
}
