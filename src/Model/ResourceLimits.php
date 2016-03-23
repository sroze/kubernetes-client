<?php

namespace Kubernetes\Client\Model;

class ResourceLimits
{
    /**
     * @var string
     */
    private $memory;

    /**
     * @var string
     */
    private $cpu;

    /**
     * @param string $memory
     * @param string $cpu
     */
    public function __construct($memory = null, $cpu = null)
    {
        $this->memory = $memory;
        $this->cpu = $cpu;
    }

    /**
     * @return string
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return string
     */
    public function getCpu()
    {
        return $this->cpu;
    }
}
