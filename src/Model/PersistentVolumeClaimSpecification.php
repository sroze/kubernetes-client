<?php

namespace Kubernetes\Client\Model;

class PersistentVolumeClaimSpecification
{
    const ACCESS_MODE_READ_WRITE_MANY = 'ReadWriteMany';
    const ACCESS_MODE_READ_ONLY_MANY = 'ReadOnlyMany';
    const ACCESS_MODE_READ_WRITE_ONCE = 'ReadWriteOnce';

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
