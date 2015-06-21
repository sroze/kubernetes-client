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
