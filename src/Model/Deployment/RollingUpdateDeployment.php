<?php

namespace Kubernetes\Client\Model\Deployment;

class RollingUpdateDeployment
{
    /**
     * The maximum number of pods that can be unavailable during the update.
     * Value can be an absolute number (ex: 5) or a percentage of total pods at the start of update (ex: 10%).
     * Absolute number is calculated from percentage by rounding up.
     * This can not be 0 if MaxSurge is 0.
     * By default, a fixed value of 1 is used.
     * Example: when this is set to 30%, the old RC can be scaled down by 30%
     * immediately when the rolling update starts. Once new pods are ready, old RC
     * can be scaled down further, followed by scaling up the new RC, ensuring
     * that at least 70% of original number of pods are available at all times
     * during the update.
     *
     * @var int|string
     */
    private $maxUnavailable;

    /**
     * The maximum number of pods that can be scheduled above the original number of
     * pods.
     * Value can be an absolute number (ex: 5) or a percentage of total pods at
     * the start of the update (ex: 10%). This can not be 0 if MaxUnavailable is 0.
     * Absolute number is calculated from percentage by rounding up.
     * By default, a value of 1 is used.
     * Example: when this is set to 30%, the new RC can be scaled up by 30%
     * immediately when the rolling update starts. Once old pods have been killed,
     * new RC can be scaled up further, ensuring that total number of pods running
     * at any time during the update is atmost 130% of original pods.
     *
     * @var int|string
     */
    private $maxSurge;

    /**
     * @param int|string $maxUnavailable
     * @param int|string $maxSurge
     */
    public function __construct($maxUnavailable, $maxSurge)
    {
        $this->maxUnavailable = $maxUnavailable;
        $this->maxSurge = $maxSurge;
    }

    /**
     * @return int|string
     */
    public function getMaxUnavailable()
    {
        return $this->maxUnavailable;
    }

    /**
     * @return int|string
     */
    public function getMaxSurge()
    {
        return $this->maxSurge;
    }
}
