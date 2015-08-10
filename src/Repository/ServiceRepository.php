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
}
