<?php

namespace Kubernetes\Client\Model;

class Ingress implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var IngressSpecification
     */
    private $specification;

    /**
     * @var IngressStatus
     */
    private $status;

    /**
     * @param ObjectMetadata       $metadata
     * @param IngressSpecification $specification
     * @param IngressStatus        $status
     */
    public function __construct(ObjectMetadata $metadata, IngressSpecification $specification, IngressStatus $status = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
        $this->status = $status;
    }

    /**
     * @return IngressSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return IngressStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return 'Ingress';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
