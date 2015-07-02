<?php

namespace Kubernetes\Client\Model;

class ServiceSpecification
{
    const SESSION_AFFINITY_CLIENT_IP = 'ClientIP';
    const SESSION_AFFINITY_NONE = 'None';

    const TYPE_CLUSTER_IP = 'ClusterIP';
    const TYPE_NODE_PORT = 'NodePort';
    const TYPE_LOAD_BALANCER = 'LoadBalancer';

    /**
     * @var array
     */
    private $selector;

    /**
     * @var ServicePort[]
     */
    private $ports;

    /**
     * @var string
     */
    private $clusterIp;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $sessionAffinity;

    public function __construct(array $selector, array $ports = [], $type = self::TYPE_CLUSTER_IP, $sessionAffinity = self::SESSION_AFFINITY_NONE, $clusterIp = null)
    {
        $this->selector = $selector;
        $this->ports = $ports;
        $this->type = $type;
        $this->sessionAffinity = $sessionAffinity;
        $this->clusterIp = $clusterIp;
    }
}
