<?php

namespace Kubernetes\Client\Model;

/**
 * Class CronJob
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class CronJob implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var CronJobSpecification
     */
    private $specification;

    /**
     * @var CronJobStatus
     */
    private $status;

    /**
     * @param ObjectMetadata   $metadata
     * @param CronJobSpecification $specification
     * @param CronJobStatus        $status
     */
    public function __construct(ObjectMetadata $metadata, CronJobSpecification $specification = null, CronJobStatus $status = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
        $this->status = $status;
    }

    /**
     * @param string $name
     *
     * @return CronJob
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
     * @return CronJobSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return CronJobStatus
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
        return 'CronJob';
    }
}
