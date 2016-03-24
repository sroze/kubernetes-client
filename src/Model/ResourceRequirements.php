<?php

namespace Kubernetes\Client\Model;

class ResourceRequirements
{
    /**
     * @var ResourceRequirementsRequests
     */
    private $requests;

    /**
     * @var ResourceLimits
     */
    private $limits;

    /**
     * @param ResourceRequirementsRequests $requests
     * @param ResourceLimits               $limits
     */
    public function __construct(ResourceRequirementsRequests $requests = null, ResourceLimits $limits = null)
    {
        $this->requests = $requests;
        $this->limits = $limits;
    }

    /**
     * @return ResourceRequirementsRequests
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @return ResourceLimits
     */
    public function getLimits()
    {
        return $this->limits;
    }
}
