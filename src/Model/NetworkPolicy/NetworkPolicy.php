<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

use Kubernetes\Client\Model\KubernetesObject;
use Kubernetes\Client\Model\ObjectMetadata;

class NetworkPolicy implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var NetworkPolicySpec
     */
    private $spec;

    public function __construct(ObjectMetadata $metadata, NetworkPolicySpec $spec)
    {
        $this->metadata = $metadata;
        $this->spec = $spec;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'NetworkPolicy';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return NetworkPolicySpec
     */
    public function getSpec(): NetworkPolicySpec
    {
        return $this->spec;
    }
}
