<?php

namespace Kubernetes\Client\Model;

class ResourceRequirementsRequests
{
    /**
     * @var string
     */
    private $storage;

    /**
     * @var string
     */
    private $memory;

    /**
     * @var string
     */
    private $cpu;

    /**
     * @param string $storage
     * @param string $memory
     * @param string $cpu
     */
    public function __construct($storage = null, $memory = null, $cpu = null)
    {
        $this->storage = $storage;
        $this->memory = $memory;
        $this->cpu = $cpu;
    }

    /**
     * @return string
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function getMemory()
    {
        return $this->memory;
    }
}
