<?php

namespace Kubernetes\Client\Model;

class Secret implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $type;

    /**
     * @param ObjectMetadata $metadata
     * @param string         $data
     * @param string         $type
     */
    public function __construct(ObjectMetadata $metadata, $data, $type = null)
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
     * @return string
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
