<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobStatus
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobStatus
{
    /**
     * @var int
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $completionTime;

    /**
     * @var JobStatusCondition[]
     */
    private $conditions;

    /**
     * @var int
     */
    private $failed;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var int
     */
    private $succeeded;

    /**
     * JobStatus constructor.
     *
     * @param int                  $active
     * @param \DateTime            $completionTime
     * @param JobStatusCondition[] $conditions
     * @param int                  $failed
     * @param \DateTime            $startTime
     * @param int                  $succeeded
     */
    public function __construct($active, \DateTime $completionTime, array $conditions, $failed, \DateTime $startTime, $succeeded)
    {
        $this->active = $active;
        $this->completionTime = $completionTime;
        $this->conditions = $conditions;
        $this->failed = $failed;
        $this->startTime = $startTime;
        $this->succeeded = $succeeded;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return \DateTime
     */
    public function getCompletionTime()
    {
        return $this->completionTime;
    }

    /**
     * @return JobStatusCondition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return int
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return int
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }
}
