<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobSpecification
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobSpecification
{
    /**
     * @var int
     */
    private $completions;

    /**
     * @var bool
     */
    private $manualSelector;

    /**
     * @var int
     */
    private $parallelism;

    /**
     * @var JobSelector
     */
    private $selector;

    /**
     * @var PodTemplateSpecification
     */
    private $template;

    /**
     * JobSpecification constructor.
     *
     * @param PodTemplateSpecification $template
     */
    public function __construct(PodTemplateSpecification $template)
    {
        $this->template = $template;
    }

    /**
     * @return int
     */
    public function getCompletions()
    {
        return $this->completions;
    }

    /**
     * @param int $completions
     *
     * @return JobSpecification
     */
    public function setCompletions(int $completions)
    {
        $this->completions = $completions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isManualSelector()
    {
        return $this->manualSelector;
    }

    /**
     * @param bool $manualSelector
     *
     * @return JobSpecification
     */
    public function setManualSelector(bool $manualSelector)
    {
        $this->manualSelector = $manualSelector;

        return $this;
    }

    /**
     * @return int
     */
    public function getParallelism()
    {
        return $this->parallelism;
    }

    /**
     * @param int $parallelism
     *
     * @return JobSpecification
     */
    public function setParallelism(int $parallelism)
    {
        $this->parallelism = $parallelism;

        return $this;
    }

    /**
     * @return JobSelector
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @param JobSelector $selector
     *
     * @return JobSpecification
     */
    public function setSelector(JobSelector $selector)
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * @return PodTemplateSpecification
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param PodTemplateSpecification $template
     *
     * @return JobSpecification
     */
    public function setTemplate(PodTemplateSpecification $template)
    {
        $this->template = $template;

        return $this;
    }
}
