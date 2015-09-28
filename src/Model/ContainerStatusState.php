<?php

namespace Kubernetes\Client\Model;

class ContainerStatusState
{
    /**
     * @var ContainerStatusStateRunning
     */
    private $running;

    /**
     * @var ContainerStatusStateTerminated
     */
    private $terminated;

    /**
     * @var ContainerStatusStateWaiting
     */
    private $waiting;

    /**
     * @return ContainerStatusStateRunning
     */
    public function getRunning()
    {
        return $this->running;
    }

    /**
     * @return ContainerStatusStateTerminated
     */
    public function getTerminated()
    {
        return $this->terminated;
    }

    /**
     * @return ContainerStatusStateWaiting
     */
    public function getWaiting()
    {
        return $this->waiting;
    }
}
