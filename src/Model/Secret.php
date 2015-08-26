<?php

namespace Kubernetes\Client\Model;

class Secret implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $type;

    /**
     * @param ObjectMetadata $metadata
     * @param array          $data
     * @param string         $type
     */
    public function __construct(ObjectMetadata $metadata, array $data, $type = null)
    {
        $this->metadata = $metadata;
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return 'Secret';
    }
}
