<?php

namespace Kubernetes\Client\Model;

class PersistentVolumeClaimSpecification
{
    /**
     * @var array<string>
     */
    private $accessModes;

    /**
     * @var ResourceRequirements
     */
    private $resources;

    /**
     * @param array                $accessModes
     * @param ResourceRequirements $resources
     */
    public function __construct(array $accessModes, ResourceRequirements $resources)
    {
        $this->accessModes = $accessModes;
        $this->resources = $resources;
    }
}
