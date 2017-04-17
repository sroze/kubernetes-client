<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\ServiceNotFound;
use Kubernetes\Client\Model\KeyValueObjectList;
use Kubernetes\Client\Model\Service;
use Kubernetes\Client\Model\ServiceList;

interface ServiceRepository
{
    /**
     * @return ServiceList
     */
    public function findAll();

    /**
     * Find all the services. The `PromiseInterface` will return a `ServiceList` object.
     *
     * @return PromiseInterface
     */
    public function asyncFindAll() : PromiseInterface;

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

    /**
     * @param string $name
     * @param KeyValueObjectList $annotations
     *
     * @return Service
     */
    public function annotate(string $name, KeyValueObjectList $annotations);
}
