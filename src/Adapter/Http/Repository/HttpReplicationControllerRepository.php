<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\Exception;
use Kubernetes\Client\Exception\ReplicationControllerNotFound;
use Kubernetes\Client\Exception\TooManyObjects;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Model\ReplicationControllerList;
use Kubernetes\Client\Repository\ReplicationControllerRepository;

class HttpReplicationControllerRepository implements ReplicationControllerRepository
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
     * @return ReplicationControllerList
     */
    public function findAll()
    {
        return $this->connector->get($this->namespaceClient->prefixPath('/replicationcontrollers'), [
            'class' => ReplicationControllerList::class,
            'groups' => ['Default', 'read'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(ReplicationController $replicationController)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/replicationcontrollers'), $replicationController, [
            'class' => ReplicationController::class,
            'groups' => ['Default', 'create'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ReplicationController $replicationController)
    {
        $name = $replicationController->getMetadata()->getName();
        $currentReplicationController = $this->findOneByName($name);

        try {
            $this->delete($currentReplicationController);
            $created = $this->create($replicationController);

            return $created;
        } catch (Exception $e) {
            // Rollback RC update by re-creating the new object
            $this->create($currentReplicationController);

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ReplicationController $replicationController)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf(
                '/replicationcontrollers/%s',
                $replicationController->getMetadata()->getName()
            ));

            return $this->connector->delete($path, [
                'class' => ReplicationController::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ReplicationControllerNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/replicationcontrollers/%s', $name));

            return $this->connector->get($path, [
                'class' => ReplicationController::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ReplicationControllerNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByLabels(array $labels)
    {
        $found = $this->findByLabels($labels);

        if ($found->count() === 0) {
            throw new ReplicationControllerNotFound();
        } elseif ($found->count() > 1) {
            throw new TooManyObjects($found->getReplicationControllers());
        }

        return $found->getReplicationControllers()[0];
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = $this->namespaceClient->createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/replicationcontrollers?labelSelector='.$labelSelector);

        /** @var ReplicationControllerList $found */
        $found = $this->connector->get($path, [
            'class' => ReplicationControllerList::class,
        ]);

        return $found;
    }
}
