<?php

namespace Kubernetes\Client\Model;

class NodeCondition
{
    /**
     * @var \DateTime
     */
    private $lastHeartbeatTime;

    /**
     * @var \DateTime
     */
    private $lastTransitionTime;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $type;
}
