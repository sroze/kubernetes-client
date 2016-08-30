<?php

namespace Kubernetes\Client\Model\Deployment;

class DeploymentStrategy
{
    const TYPE_RECREATE = 'Recreate';
    const TYPE_ROLLING_UPDATE = 'RollingUpdate';

    /**
     * @var string
     */
    private $type;

    /**
     * Rolling update config params. Present only if DeploymentStrategyType = RollingUpdate.
     *
     * @var RollingUpdateDeployment
     */
    private $rollingUpdate;

    /**
     * @param string                  $type
     * @param RollingUpdateDeployment $rollingUpdate
     */
    public function __construct($type, RollingUpdateDeployment $rollingUpdate = null)
    {
        $this->type = $type;
        $this->rollingUpdate = $rollingUpdate;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return RollingUpdateDeployment
     */
    public function getRollingUpdate()
    {
        return $this->rollingUpdate;
    }
}
