<?php

namespace Kubernetes\Client\Model;

class ContainerStatusStateRunning
{
    /**
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }
}
