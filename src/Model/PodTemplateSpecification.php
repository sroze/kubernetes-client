<?php

namespace Kubernetes\Client\Model;

class PodTemplateSpecification
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var PodSpecification
     */
    private $podSpecification;

    /**
     * @param ObjectMetadata   $metadata
     * @param PodSpecification $podSpecification
     */
    public function __construct(ObjectMetadata $metadata, PodSpecification $podSpecification)
    {
        $this->metadata = $metadata;
        $this->podSpecification = $podSpecification;
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return PodSpecification
     */
    public function getPodSpecification()
    {
        return $this->podSpecification;
    }
}
