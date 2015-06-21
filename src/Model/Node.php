<?php

namespace Kubernetes\Client\Model;

class Node
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var NodeSpecification
     */
    private $specification;

    /**
     * @var NodeStatus
     */
    private $status;

    /**
     * @param ObjectMetadata    $metadata
     * @param NodeSpecification $specification
     */
    public function __construct(ObjectMetadata $metadata, NodeSpecification $specification = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
    }
}
