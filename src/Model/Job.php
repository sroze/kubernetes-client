<?php

namespace Kubernetes\Client\Model;

/**
 * Class Job
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class Job implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var JobSpecification
     */
    private $specification;

    /**
     * @var JobStatus
     */
    private $status;

    /**
     * @param ObjectMetadata   $metadata
     * @param JobSpecification $specification
     * @param JobStatus        $status
     */
    public function __construct(ObjectMetadata $metadata, JobSpecification $specification = null, JobStatus $status = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
        $this->status = $status;
    }

    /**
     * @param string $name
     *
     * @return Job
     */
    public static function fromName($name)
    {
        return new self(new ObjectMetadata($name));
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
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return JobStatus
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
        return 'Job';
    }
}
