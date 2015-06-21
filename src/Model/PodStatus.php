<?php

namespace Kubernetes\Client\Model;

class PodStatus
{
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
