<?php

namespace Kubernetes\Client\Model;

class Deployment implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var DeploymentSpecification
     */
    private $specification;

    /**
     * @var DeploymentStatus
     */
    private $status;

    /**
     * @param ObjectMetadata          $metadata
     * @param DeploymentSpecification $specification
     * @param DeploymentStatus        $status
     */
    public function __construct(ObjectMetadata $metadata, DeploymentSpecification $specification, DeploymentStatus $status = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
        $this->status = $status;
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return DeploymentSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return DeploymentStatus|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'Deployment';
    }
}
