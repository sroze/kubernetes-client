<?php

namespace Kubernetes\Client\Model\Volume;

class PersistentVolumeClaimSource
{
    /**
     * @var string
     */
    private $claimName;

    /**
     * @param string $claimName
     */
    public function __construct($claimName)
    {
        $this->claimName = $claimName;
    }

    /**
     * @return string
     */
    public function getClaimName()
    {
        return $this->claimName;
    }
}
