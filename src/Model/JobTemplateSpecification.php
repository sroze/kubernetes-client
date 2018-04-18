<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobTemplateSpecification
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobTemplateSpecification
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var JobSpecification
     */
    private $podSpecification;

    /**
     * @param ObjectMetadata   $metadata
     * @param JobSpecification $podSpecification
     */
    public function __construct(ObjectMetadata $metadata, JobSpecification $podSpecification)
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
     * @return JobSpecification
     */
    public function getJobSpecification()
    {
        return $this->podSpecification;
    }
}
