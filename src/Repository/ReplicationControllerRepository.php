<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\ReplicationControllerNotFound;
use Kubernetes\Client\Exception\TooManyObjects;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Model\ReplicationControllerList;

interface ReplicationControllerRepository
{
    /**
     * @return ReplicationControllerList
     */
    public function findAll();

    /**
     * @return PromiseInterface
     */
    public function asyncFindAll();

    /**
     * @param ReplicationController $replicationController
     *
     * @return ReplicationController
     */
    public function create(ReplicationController $replicationController);

    /**
     * @param ReplicationController $replicationController
     *
     * @throws ReplicationControllerNotFound
     *
     * @return ReplicationController
     */
    public function update(ReplicationController $replicationController);

    /**
     * @param ReplicationController $replicationController
     *
     * @throws ReplicationControllerNotFound
     */
    public function delete(ReplicationController $replicationController);

    /**
     * @param string $name
     *
     * @throws TooManyObjects
     * @throws ReplicationControllerNotFound
     *
     * @return ReplicationController
     */
    public function findOneByName($name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param array $labels
     *
     * @throws TooManyObjects
     * @throws ReplicationControllerNotFound
     *
     * @return ReplicationController
     */
    public function findOneByLabels(array $labels);

    /**
     * @param array $labels
     *
     * @return ReplicationControllerList
     */
    public function findByLabels(array $labels);
}
