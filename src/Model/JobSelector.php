<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobSelector
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobSelector
{
    /**
     * @var array<string,string>
     */
    private $matchLabels = [];

    /**
     * @var JobSelectorRequirement[]
     */
    private $matchExpressions = [];

    /**
     * @param array                    $matchLabels
     * @param JobSelectorRequirement[] $matchExpressions
     */
    public function __construct(array $matchLabels = null, array $matchExpressions = null)
    {
        $this->matchLabels = $matchLabels;
        $this->matchExpressions = $matchExpressions;
    }

    /**
     * @return array
     */
    public function getMatchLabels()
    {
        return $this->matchLabels;
    }

    /**
     * @return JobSelectorRequirement[]
     */
    public function getMatchExpressions()
    {
        return $this->matchExpressions;
    }
}
