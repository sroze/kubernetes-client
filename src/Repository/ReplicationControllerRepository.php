<?php

namespace Kubernetes\Client\Repository;

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
