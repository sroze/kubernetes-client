<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Model\Deployment\DeploymentStrategy;
use Kubernetes\Client\Model\Deployment\RollbackConfiguration;

class DeploymentSpecification
{
    /**
     * Number of desired pods. This is a pointer to distinguish between explicit
     * zero and not specified. Defaults to 1.
     *
     * @var int
     */
    private $replicas;

    /**
     * Label selector for pods. Existing ReplicaSets whose pods are
     * selected by this will be the ones affected by this deployment.
     *
     * @var array<string,string>
     */
    private $selector;

    /**
     * Template describes the pods that will be created.
     *
     * @var PodTemplateSpecification
     */
    private $template;

    /**
     * The deployment strategy to use to replace existing pods with new ones.
     *
     * @var DeploymentStrategy
     */
    private $strategy;

    /**
     * Minimum number of seconds for which a newly created pod should be ready
     * without any of its container crashing, for it to be considered available.
     * Defaults to 0 (pod will be considered available as soon as it is ready).
     *
     * @var int
     */
    private $minReadySeconds;

    /**
     * The number of old ReplicaSets to retain to allow rollback.
     * This is a pointer to distinguish between explicit zero and not specified.
     *
     * @var int
     */
    private $revisionHistoryLimit;

    /**
     * Indicates that the deployment is paused and will not be processed by the
     * deployment controller.
     *
     * @var bool
     */
    private $paused;

    /**
     * The config this deployment is rolling back to. Will be cleared after rollback is done.
     *
     * @var RollbackConfiguration
     */
    private $rollbackTo;

    /**
     * @param int                      $replicas
     * @param array                    $selector
     * @param PodTemplateSpecification $template
     * @param DeploymentStrategy       $strategy
     * @param int                      $minReadySeconds
     * @param int                      $revisionHistoryLimit
     * @param bool                     $paused
     * @param RollbackConfiguration    $rollbackTo
     */
    public function __construct(
        $replicas,
        array $selector,
        PodTemplateSpecification $template,
        DeploymentStrategy $strategy,
        $minReadySeconds = null,
        $revisionHistoryLimit = null,
        $paused = null,
        RollbackConfiguration $rollbackTo = null
    ) {
        $this->replicas = $replicas;
        $this->selector = $selector;
        $this->template = $template;
        $this->strategy = $strategy;
        $this->minReadySeconds = $minReadySeconds;
        $this->revisionHistoryLimit = $revisionHistoryLimit;
        $this->paused = $paused;
        $this->rollbackTo = $rollbackTo;
    }

    /**
     * @return int
     */
    public function getReplicas()
    {
        return $this->replicas;
    }

    /**
     * @return array
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return PodTemplateSpecification
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return DeploymentStrategy
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @return int
     */
    public function getMinReadySeconds()
    {
        return $this->minReadySeconds;
    }

    /**
     * @return int
     */
    public function getRevisionHistoryLimit()
    {
        return $this->revisionHistoryLimit;
    }

    /**
     * @return bool
     */
    public function isPaused()
    {
        return $this->paused;
    }

    /**
     * @return RollbackConfiguration
     */
    public function getRollbackTo()
    {
        return $this->rollbackTo;
    }
}
