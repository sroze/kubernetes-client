<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Exception\PodNotFound;

/**
 * Provide up to date status information of a pod.
 *
 * @package Kubernetes\Client\Repository
 */
interface PodStatusProvider
{
    /**
     * Tell whether the pod and it's containers has been terminated.
     *
     * @param Pod $pod
     * @return bool
     *
     * @throws PodNotFound
     */
    public function isTerminated(Pod $pod);

    /**
     * Tell whether the pod is in pending status.
     *
     * @param Pod $pod
     * @return bool
     *
     * @throws PodNotFound
     */
    public function isPending(Pod $pod);
}
