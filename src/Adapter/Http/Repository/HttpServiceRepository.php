<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ServiceNotFound;
use Kubernetes\Client\Model\Service;
use Kubernetes\Client\Model\ServiceList;
use Kubernetes\Client\Model\Status;
use Kubernetes\Client\Repository\ServiceRepository;

class HttpServiceRepository implements ServiceRepository
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @param HttpConnector       $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get($this->namespaceClient->prefixPath('/services'), [
            'class' => ServiceList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/services/%s', $name)), [
                'class' => Service::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ServiceNotFound(sprintf(
                    'Service named "%s" is not found',
                    $name
                ));
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (ServiceNotFound $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Service $service)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/services'), $service, [
            'class' => Service::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Service $service)
    {
        $serviceName = $service->getMetadata()->getName();
        $currentService = $this->findOneByName($serviceName);

        if ($service->getSpecification() == $currentService->getSpecification()) {
            return $currentService;
        }

        $path = sprintf('/services/%s', $serviceName);

        return $this->connector->patch($this->namespaceClient->prefixPath($path), $service, [
            'class' => Service::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Service $service)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/services/%s', $service->getMetadata()->getName()));

            return $this->connector->delete($path, null, [
                'class' => Status::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ServiceNotFound();
            }

            throw $e;
        }
    }
}
