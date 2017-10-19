<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Exception\PodNotFound;

/**
 * Provide up to date status information of a pod.
 *
 * @package Kubernetes\Client\Repository
 */
interface PodAwareStatusProvider
{
    /**
     * Change the current pod to the given one.
     *
     * @param Pod $pod
     *
     * @return PodAwareStatusProvider
     */
    public function setPod(Pod $pod);
    
    /**
     * Tell whether the pod and it's containers has been terminated.
     *
     * @return bool
     *
     * @throws PodNotFound
     */
    public function isTerminated();

    /**
     * Tell whether the pod is in pending status.
     *
     * @return bool
     *
     * @throws PodNotFound
     */
    public function isPending();
}
