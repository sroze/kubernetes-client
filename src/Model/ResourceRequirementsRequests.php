<?php

namespace Kubernetes\Client\Model;

class ResourceRequirementsRequests
{
    /**
     * @var string
     */
    private $storage;

    /**
     * @param string $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
}
