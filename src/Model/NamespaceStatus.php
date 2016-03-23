<?php

namespace Kubernetes\Client\Model;

class NamespaceStatus
{
    const PHASE_ACTIVE = 'Active';
    const PHASE_TERMINATING = 'Terminating';

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
