<?php

namespace Kubernetes\Client\Model\Deployment;

class RollbackConfiguration
{
    /**
     * @var int
     */
    private $revision;

    /**
     * @param int $revision
     */
    public function __construct($revision)
    {
        $this->revision = $revision;
    }

    /**
     * @return int
     */
    public function getRevision()
    {
        return $this->revision;
    }
}
