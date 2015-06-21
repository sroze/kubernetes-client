<?php

namespace Kubernetes\Client\Model;

class NodeStatus
{
    /**
     * @var NodeAddress[]
     */
    private $addresses;

    /**
     * @var string
     */
    private $capacity;

    /**
     * @var string
     */
    private $phase;

    /**
     * @var NodeCondition[]
     */
    private $conditions;

    /**
     * @var NodeSystemInfo
     */
    private $nodeInfo;
}
