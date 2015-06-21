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
     * @return Service|null
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
