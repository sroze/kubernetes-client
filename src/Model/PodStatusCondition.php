<?php

namespace Kubernetes\Client\Model;

class PodStatusCondition
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $status;

    /**
     * @param string $type
     * @param bool   $status
     */
    public function __construct($type, $status)
    {
        $this->type = $type;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }
}
