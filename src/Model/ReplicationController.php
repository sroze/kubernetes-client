<?php

namespace Kubernetes\Client\Model;

class ReplicationController implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var ReplicationControllerSpecification
     */
    private $specification;

    /**
     * @param ObjectMetadata                     $metadata
     * @param ReplicationControllerSpecification $specification
     */
    public function __construct(ObjectMetadata $metadata, ReplicationControllerSpecification $specification = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return ReplicationControllerSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'ReplicationController';
    }
}
