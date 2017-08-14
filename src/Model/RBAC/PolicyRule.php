<?php

namespace Kubernetes\Client\Model\RBAC;

class PolicyRule
{
    /**
     * @var string[]
     */
    private $apiGroups;

    /**
     * @var string[]
     */
    private $resources;

    /**
     * @var string[]
     */
    private $verbs;

    /**
     * @var string[]
     */
    private $resourceNames;

    /**
     * @var string[]
     */
    private $nonResourceURLs;

    /**
     * @param string[] $apiGroups
     * @param string[] $resources
     * @param string[] $verbs
     * @param string[] $resourceNames
     * @param string[] $nonResourceURLs
     */
    public function __construct(array $apiGroups, array $resources, array $verbs, array $resourceNames = [], array $nonResourceURLs = [])
    {
        $this->apiGroups = $apiGroups;
        $this->resources = $resources;
        $this->verbs = $verbs;
        $this->resourceNames = $resourceNames;
        $this->nonResourceURLs = $nonResourceURLs;
    }

    /**
     * @return string[]
     */
    public function getApiGroups(): array
    {
        return $this->apiGroups ?: [];
    }

    /**
     * @return string[]
     */
    public function getResources(): array
    {
        return $this->resources ?: [];
    }

    /**
     * @return string[]
     */
    public function getVerbs(): array
    {
        return $this->verbs ?: [];
    }

    /**
     * @return string[]
     */
    public function getResourceNames(): array
    {
        return $this->resourceNames ?: [];
    }

    /**
     * @return string[]
     */
    public function getNonResourceURLs(): array
    {
        return $this->nonResourceURLs ?: [];
    }
}
