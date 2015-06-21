<?php

namespace Kubernetes\Client\Model;

class PodSpecification
{
    const RESTART_POLICY_ALWAYS = 'Always';
    const RESTART_POLICY_ON_FAILURE = 'OnFailure';
    const RESTART_POLICY_NEVER = 'Never';

    const DNS_POLICY_CLUSTER_FIRST = 'ClusterFirst';
    const DNS_POLICY_DEFAULT = 'Default';

    /**
     * @var Container[]
     */
    private $containers;
    /**
     * @var Volume[]
     */
    private $volumes;
    /**
     * @var string
     */
    private $restartPolicy;
    /**
     * @var string
     */
    private $dnsPolicy;

    public function __construct(array $containers, array $volumes = [], $restartPolicy = self::RESTART_POLICY_ALWAYS, $dnsPolicy = self::DNS_POLICY_CLUSTER_FIRST)
    {
        $this->containers = $containers;
        $this->volumes = $volumes;
        $this->restartPolicy = $restartPolicy;
        $this->dnsPolicy = $dnsPolicy;
    }

    /**
     * @return Container[]
     */
    public function getContainers()
    {
        return $this->containers;
    }
}
