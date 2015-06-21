<?php

namespace Kubernetes\Client\Model;

class ReplicationControllerSpecification
{
    /**
     * Number of replicas.
     *
     * @var int
     */
    private $replicas;

    /**
     * Label selector to identify replication controller's pods.
     *
     * If empty, it defaults to the labels of Pod template.
     *
     * @var array
     */
    private $selector;

    /**
     * @var PodTemplateSpecification
     */
    private $podTemplateSpecification;

    public function __construct($replicas, $selector, PodTemplateSpecification $podTemplateSpecification)
    {
        $this->replicas = $replicas;
        $this->selector = $selector;
        $this->podTemplateSpecification = $podTemplateSpecification;
    }

    /**
     * @return int
     */
    public function getReplicas()
    {
        return $this->replicas;
    }

    /**
     * @return array
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return PodTemplateSpecification
     */
    public function getPodTemplateSpecification()
    {
        return $this->podTemplateSpecification;
    }

    /**
     * @param array $selector
     */
    public function setSelector(array $selector)
    {
        $this->selector = $selector;
    }

    /**
     * @param int $replicas
     */
    public function setReplicas($replicas)
    {
        $this->replicas = $replicas;
    }
}
