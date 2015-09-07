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
     * @var bool
     */
    private $ready;

    /**
     * @param string               $name
     * @param int                  $restartCount
     * @param string               $containerId
     * @param ContainerStatusState $state
     * @param bool                 $ready
     */
    public function __construct($name, $restartCount, $containerId, $state, $ready)
    {
        $this->name = $name;
        $this->restartCount = $restartCount;
        $this->containerId = $containerId;
        $this->state = $state;
        $this->ready = $ready;
    }

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

    /**
     * @return bool
     */
    public function isReady()
    {
        return $this->ready;
    }
}
