<?php

namespace Kubernetes\Client\Model;

class NamespaceStatus
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
