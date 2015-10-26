<?php

namespace Kubernetes\Client\Model;

class PersistentVolumeClaim implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var PersistentVolumeClaimSpecification
     */
    private $specification;

    /**
     * @var PersistentVolumeClaimStatus
     */
    private $status;

    /**
     * @param ObjectMetadata                     $metadata
     * @param PersistentVolumeClaimSpecification $specification
     * @param PersistentVolumeClaimStatus        $status
     */
    public function __construct(ObjectMetadata $metadata, PersistentVolumeClaimSpecification $specification, PersistentVolumeClaimStatus $status = null)
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
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'PersistentVolumeClaim';
    }
}
