<?php

namespace Kubernetes\Client\Model;

class KubernetesNamespace
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @param ObjectMetadata $metadata
     */
    public function __construct(ObjectMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
