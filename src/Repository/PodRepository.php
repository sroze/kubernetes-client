<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodList;
use Kubernetes\Client\Model\ReplicationController;

interface PodRepository
{
    /**
     * @return PodList
     */
    public function findAll();

    /**
     * Find all the pods. The promise will return a `PodList` object.
     *
     * @return PromiseInterface
     */
    public function asyncFindAll() : PromiseInterface;

    /**
     * @param array $labels
     *
     * @return PodList
     */
    public function findByLabels(array $labels);

    /**
     * @param Pod $pod
     *
     * @return Pod
     */
    public function create(Pod $pod);

    /**
     * @param Pod $pod
     *
     * @return Pod
     */
    public function update(Pod $pod);

    /**
     * @param string $name
     *
     * @throws PodNotFound
     *
     * @return Pod
     */
    public function findOneByName($name);

    /**
     * Check if the pod with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param Pod $pod
     *
     * @throws PodNotFound
     */
    public function delete(Pod $pod);

    /**
     * @param ReplicationController $replicationController
     *
     * @return PodList
     */
    public function findByReplicationController(ReplicationController $replicationController);

    /**
     * @param Pod      $pod
     * @param callable $callable
     *
     * @return Pod
     */
    public function attach(Pod $pod, callable $callable);
}
