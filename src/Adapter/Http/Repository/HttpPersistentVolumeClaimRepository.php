<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\PersistentVolumeClaimNotFound;
use Kubernetes\Client\Model\PersistentVolumeClaim;
use Kubernetes\Client\Repository\PersistentVolumeClaimRepository;

class HttpPersistentVolumeClaimRepository implements PersistentVolumeClaimRepository
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
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/persistentvolumeclaims/%s', $name)), [
                'class' => PersistentVolumeClaim::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new PersistentVolumeClaimNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(PersistentVolumeClaim $claim)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/persistentvolumeclaims'), $claim, [
            'class' => PersistentVolumeClaim::class,
        ]);
    }
}
