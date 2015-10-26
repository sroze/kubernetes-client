<?php

namespace Kubernetes\Client\Model;

class PersistentVolumeClaimStatus
{
    /**
     * @var string
     */
    private $phase;

    /**
     * @return string
     */
    public function getPhase()
    {
        return $this->phase;
    }
}
