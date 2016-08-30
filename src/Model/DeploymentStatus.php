<?php

namespace Kubernetes\Client\Model;

class DeploymentStatus
{
    /**
     * @var int
     */
    private $observedGeneration;

    /**
     * @var int
     */
    private $replicas;

    /**
     * @var int
     */
    private $updatedReplicas;

    /**
     * @var int
     */
    private $availableReplicas;

    /**
     * @var int
     */
    private $unavailableReplicas;

    /**
     * @param int $observedGeneration
     * @param int $replicas
     * @param int $updatedReplicas
     * @param int $availableReplicas
     * @param int $unavailableReplicas
     */
    public function __construct($observedGeneration, $replicas, $updatedReplicas, $availableReplicas, $unavailableReplicas)
    {
        $this->observedGeneration = $observedGeneration;
        $this->replicas = $replicas;
        $this->updatedReplicas = $updatedReplicas;
        $this->availableReplicas = $availableReplicas;
        $this->unavailableReplicas = $unavailableReplicas;
    }

    /**
     * @return int
     */
    public function getObservedGeneration()
    {
        return $this->observedGeneration;
    }

    /**
     * @return int
     */
    public function getReplicas()
    {
        return $this->replicas;
    }

    /**
     * @return int
     */
    public function getUpdatedReplicas()
    {
        return $this->updatedReplicas;
    }

    /**
     * @return int
     */
    public function getAvailableReplicas()
    {
        return $this->availableReplicas;
    }

    /**
     * @return int
     */
    public function getUnavailableReplicas()
    {
        return $this->unavailableReplicas;
    }
}
