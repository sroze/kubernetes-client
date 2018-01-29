<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

use Kubernetes\Client\Model\IPBlock;
use Kubernetes\Client\Model\LabelSelector;

class NetworkPolicyPeer
{
    /**
     * @var IPBlock|null
     */
    private $ipBlock;

    /**
     * @var LabelSelector|null
     */
    private $namespaceSelector;

    /**
     * @var LabelSelector|null
     */
    private $podSelector;

    public function __construct(LabelSelector $namespaceSelector = null, LabelSelector $podSelector = null, IPBlock $ipBlock = null)
    {
        $this->ipBlock = $ipBlock;
        $this->namespaceSelector = $namespaceSelector;
        $this->podSelector = $podSelector;
    }

    /**
     * @return IPBlock|null
     */
    public function getIpBlock()
    {
        return $this->ipBlock;
    }

    /**
     * @return LabelSelector|null
     */
    public function getNamespaceSelector()
    {
        return $this->namespaceSelector;
    }

    /**
     * @return LabelSelector|null
     */
    public function getPodSelector()
    {
        return $this->podSelector;
    }
}
