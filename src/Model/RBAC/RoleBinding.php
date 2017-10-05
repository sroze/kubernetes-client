<?php

namespace Kubernetes\Client\Model\RBAC;

use Kubernetes\Client\Model\KubernetesObject;
use Kubernetes\Client\Model\ObjectMetadata;

class RoleBinding implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var RoleRef
     */
    private $roleRef;

    /**
     * @var array|Subject[]
     */
    private $subjects;

    /**
     * @param ObjectMetadata $metadata
     * @param RoleRef $roleRef
     * @param Subject[] $subjects
     */
    public function __construct(ObjectMetadata $metadata, RoleRef $roleRef, array $subjects)
    {
        $this->metadata = $metadata;
        $this->roleRef = $roleRef;
        $this->subjects = $subjects;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'RoleBinding';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(): ObjectMetadata
    {
        return $this->metadata;
    }

    /**
     * @return RoleRef
     */
    public function getRoleRef(): RoleRef
    {
        return $this->roleRef;
    }

    /**
     * @return array|Subject[]
     */
    public function getSubjects()
    {
        return $this->subjects ?: [];
    }
}
