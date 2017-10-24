<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Exception\PodNotFound;
use React\EventLoop\LoopInterface;
use React\Stream\ReadableStreamInterface;

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

    /**
     * Provide standard output of the pod to the given callback function.
     *
     * This is a synchronous method, which means the call is blocked until detached.
     *
     * @param Pod $pod
     * @param callable $callable
     *
     * @return Pod The updated pod instance.
     */
    public function attach(Pod $pod, callable $callable);

    /**
     * Create a standard output stream for the given pod.
     *
     * This method is an asynchronous version of the {@see PodStatusProvider::attach()} method.
     *
     * @param Pod $pod
     * @param LoopInterface $loop
     *
     * @return ReadableStreamInterface
     */
    public function streamOutput(Pod $pod, LoopInterface $loop): ReadableStreamInterface;
}
