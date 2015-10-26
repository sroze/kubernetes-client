<?php

namespace Kubernetes\Client\Model;

class SecurityContext
{
    /**
     * @var bool
     */
    private $privileged;

    /**
     * @param bool $privileged
     */
    public function __construct($privileged)
    {
        $this->privileged = $privileged;
    }

    /**
     * @return bool
     */
    public function isPrivileged()
    {
        return $this->privileged;
    }
}
