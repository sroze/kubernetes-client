<?php

namespace Kubernetes\Client\Model;

/**
 * Class CronJobStatus
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class CronJobStatus
{
    /**
     * @var ObjectReference[]
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $lastScheduleTime;

    /**
     * CronJobStatus constructor.
     *
     * @param ObjectReference[] $active
     * @param \DateTime         $lastScheduleTime
     */
    public function __construct(array $active = [], \DateTime $lastScheduleTime = null)
    {
        $this->active = $active;
        $this->lastScheduleTime = $lastScheduleTime;
    }

    /**
     * @return ObjectReference[]
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return \DateTime
     */
    public function getLastScheduleTime()
    {
        return $this->lastScheduleTime;
    }
}
