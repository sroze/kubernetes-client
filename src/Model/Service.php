<?php

namespace Kubernetes\Client\Model;

class Service implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var ServiceSpecification
     */
    private $specification;

    /**
     * @param ObjectMetadata       $metadata
     * @param ServiceSpecification $specification
     */
    public function __construct(ObjectMetadata $metadata, ServiceSpecification $specification = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
    }

    /**
     * @param string $name
     *
     * @return Pod
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
     * @return ServiceSpecification
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
        return 'Service';
    }
}
