<?php

namespace Kubernetes\Client\Model;

/**
 * Class CronJobSpecification
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class CronJobSpecification
{
    const POLICY_ALLOW = 'Allow';
    const POLICY_FORBID = 'Forbid';
    const POLICY_REPLACE = 'Replace';

    /**
     * @var string
     */
    private $schedule;

    /**
     * @var JobTemplateSpecification
     */
    private $jobTemplate;

    /**
     * @var string
     */
    private $concurrencyPolicy;

    /**
     * @var int
     */
    private $failedJobsHistoryLimit;

    /**
     * @var int
     */
    private $startingDeadlineSeconds;

    /**
     * @var int
     */
    private $successfulJobsHistoryLimit;

    /**
     * @var bool
     */
    private $suspend;

    /**
     * CronJobSpecification constructor.
     *
     * @param string                   $schedule
     * @param JobTemplateSpecification $jobTemplate
     */
    public function __construct(string $schedule, JobTemplateSpecification $jobTemplate)
    {
        $this->schedule = $schedule;
        $this->jobTemplate = $jobTemplate;
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     *
     * @return CronJobSpecification
     */
    public function setSchedule(string $schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return JobTemplateSpecification
     */
    public function getJobTemplate()
    {
        return $this->jobTemplate;
    }

    /**
     * @param JobTemplateSpecification $jobTemplate
     *
     * @return CronJobSpecification
     */
    public function setJobTemplate(JobTemplateSpecification $jobTemplate)
    {
        $this->jobTemplate = $jobTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getConcurrencyPolicy()
    {
        return $this->concurrencyPolicy;
    }

    /**
     * @param string $concurrencyPolicy
     *
     * @return CronJobSpecification
     */
    public function setConcurrencyPolicy(string $concurrencyPolicy)
    {
        $this->concurrencyPolicy = $concurrencyPolicy;

        return $this;
    }

    /**
     * @return int
     */
    public function getFailedJobsHistoryLimit()
    {
        return $this->failedJobsHistoryLimit;
    }

    /**
     * @param int $failedJobsHistoryLimit
     *
     * @return CronJobSpecification
     */
    public function setFailedJobsHistoryLimit(int $failedJobsHistoryLimit)
    {
        $this->failedJobsHistoryLimit = $failedJobsHistoryLimit;

        return $this;
    }

    /**
     * @return int
     */
    public function getStartingDeadlineSeconds()
    {
        return $this->startingDeadlineSeconds;
    }

    /**
     * @param int $startingDeadlineSeconds
     *
     * @return CronJobSpecification
     */
    public function setStartingDeadlineSeconds(int $startingDeadlineSeconds)
    {
        $this->startingDeadlineSeconds = $startingDeadlineSeconds;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuccessfulJobsHistoryLimit()
    {
        return $this->successfulJobsHistoryLimit;
    }

    /**
     * @param int $successfulJobsHistoryLimit
     *
     * @return CronJobSpecification
     */
    public function setSuccessfulJobsHistoryLimit(int $successfulJobsHistoryLimit)
    {
        $this->successfulJobsHistoryLimit = $successfulJobsHistoryLimit;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return $this->suspend;
    }

    /**
     * @param bool $suspend
     *
     * @return CronJobSpecification
     */
    public function setSuspend(bool $suspend)
    {
        $this->suspend = $suspend;

        return $this;
    }
}
