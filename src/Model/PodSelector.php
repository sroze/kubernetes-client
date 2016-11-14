<?php

namespace Kubernetes\Client\Model;

class PodSelector
{
    /**
     * @var array<string,string>
     */
    private $matchLabels = [];

    /**
     * @var PodSelectorRequirement[]
     */
    private $matchExpressions = [];

    /**
     * @param array                    $matchLabels
     * @param PodSelectorRequirement[] $matchExpressions
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
     * @return PodSelectorRequirement[]
     */
    public function getMatchExpressions()
    {
        return $this->matchExpressions;
    }
}
