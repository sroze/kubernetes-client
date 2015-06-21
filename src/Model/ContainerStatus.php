<?php

namespace Kubernetes\Client\Model;

class ContainerStatus
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $restartCount;

    /**
     * @var string
     */
    private $containerId;

    /**
     * @var ContainerStatusState
     */
    private $state;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRestartCount()
    {
        return $this->restartCount;
    }

    /**
     * @return string
     */
    public function getContainerId()
    {
        return $this->containerId;
    }

    /**
     * @return ContainerStatusState
     */
    public function getState()
    {
        return $this->state;
    }
}
