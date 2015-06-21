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
    private $termination;

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
    public function getTermination()
    {
        return $this->termination;
    }

    /**
     * @return ContainerStatusStateWaiting
     */
    public function getWaiting()
    {
        return $this->waiting;
    }
}
