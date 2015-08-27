<?php

namespace Kubernetes\Client\Model;

class ServiceAccount implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var ObjectReference[]
     */
    private $secrets;

    /**
     * @var LocalObjectReference[]
     */
    private $imagePullSecrets;

    /**
     * @param ObjectMetadata         $metadata
     * @param ObjectReference[]      $secrets
     * @param LocalObjectReference[] $imagePullSecrets
     */
    public function __construct(ObjectMetadata $metadata, array $secrets, array $imagePullSecrets)
    {
        $this->metadata = $metadata;
        $this->secrets = $secrets;
        $this->imagePullSecrets = $imagePullSecrets;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'ServiceAccount';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return ObjectReference[]
     */
    public function getSecrets()
    {
        return $this->secrets ?: [];
    }

    /**
     * @return LocalObjectReference[]
     */
    public function getImagePullSecrets()
    {
        return $this->imagePullSecrets ?: [];
    }
}
