<?php

namespace Kubernetes\Client\Model\RBAC;

use Kubernetes\Client\Model\KubernetesObject;
use Kubernetes\Client\Model\ObjectMetadata;

class Role implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var array|PolicyRule[]
     */
    private $rules;

    /**
     * @param ObjectMetadata $metadata
     * @param PolicyRule[]   $rules
     */
    public function __construct(ObjectMetadata $metadata, array $rules)
    {
        $this->metadata = $metadata;
        $this->rules = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'Role';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return array|PolicyRule[]
     */
    public function getRules()
    {
        return $this->rules ?: [];
    }
}
