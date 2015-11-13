<?php

namespace Kubernetes\Client\Model;

class PodStatus
{
    const PHASE_PENDING = 'Pending';
    const PHASE_RUNNING = 'Running';
    const PHASE_SUCCEEDED = 'Succeeded';
    const PHASE_FAILED = 'Failed';

    /**
     * @var string
     */
    private $phase;

    /**
     * @var string
     */
    private $hostIp;

    /**
     * @var string
     */
    private $podIp;

    /**
     * @var PodStatusCondition[]
     */
    private $conditions = [];

    /**
     * @var ContainerStatus[]
     */
    private $containerStatuses = [];

    /**
     * @param string               $phase
     * @param string               $hostIp
     * @param string               $podIp
     * @param PodStatusCondition[] $conditions
     * @param ContainerStatus[]    $containerStatuses
     */
    public function __construct($phase, $hostIp, $podIp, array $conditions, array $containerStatuses)
    {
        $this->phase = $phase;
        $this->hostIp = $hostIp;
        $this->podIp = $podIp;
        $this->conditions = $conditions;
        $this->containerStatuses = $containerStatuses;
    }

    /**
     * @return string
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * @return PodStatusCondition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return string
     */
    public function getHostIp()
    {
        return $this->hostIp;
    }

    /**
     * @return string
     */
    public function getPodIp()
    {
        return $this->podIp;
    }

    /**
     * @return ContainerStatus[]
     */
    public function getContainerStatuses()
    {
        return $this->containerStatuses;
    }
}
