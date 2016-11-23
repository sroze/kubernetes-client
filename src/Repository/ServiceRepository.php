<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\ServiceNotFound;
use Kubernetes\Client\Model\Service;
use Kubernetes\Client\Model\ServiceList;

interface ServiceRepository
{
    /**
     * @return ServiceList
     */
    public function findAll();

    /**
     * @param string $name
     *
     * @throws ServiceNotFound
     *
     * @return Service
     */
    public function findOneByName($name);

    /**
     * @param array $labels
     *
     * @return ServiceList
     */
    public function findByLabels(array $labels);

    /**
     * Test if a service with that name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param Service $service
     *
     * @return Service
     */
    public function create(Service $service);

    /**
     * @param Service $service
     *
     * @throws ServiceNotFound
     */
    public function delete(Service $service);

    /**
     * @param Service $service
     *
     * @return Service
     */
    public function update(Service $service);
}
