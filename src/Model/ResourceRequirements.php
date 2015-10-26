<?php

namespace Kubernetes\Client\Model;

class ResourceRequirements
{
    /**
     * @var ResourceRequirementsRequests
     */
    private $requests;

    /**
     * @param ResourceRequirementsRequests $requests
     */
    public function __construct(ResourceRequirementsRequests $requests)
    {
        $this->requests = $requests;
    }
}
