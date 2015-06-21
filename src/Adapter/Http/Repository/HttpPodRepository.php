<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\Connector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Exception\ReplicationControllerNotFound;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodList;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\PodRepository;

class HttpPodRepository implements PodRepository
{
    /**
     * @var Connector
     */
    private $connector;
    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @param Connector           $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(Connector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get($this->namespaceClient->prefixPath('/pods'), [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = $this->namespaceClient->createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/pods?labelSelector='.$labelSelector);

        return $this->connector->get($path, [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Pod $pod)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/pods'), $pod, [
            'class' => Pod::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Pod $pod)
    {
        $path = sprintf('/pods/%s', $pod->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path), $pod, [
            'class' => Pod::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/pods/%s', $name)), [
                'class' => Pod::class,
                'groups' => ['Default', 'show'],
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
    public function delete(Pod $pod)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/pods/%s', $pod->getMetadata()->getName()));

            return $this->connector->delete($path, [
                'class' => Pod::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new PodNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByReplicationController(ReplicationController $replicationController)
    {
        $selector = $replicationController->getSpecification()->getSelector();
        $labelSelector = $this->namespaceClient->createLabelSelector($selector);

        $path = '/pods?labelSelector='.urlencode($labelSelector);

        return $this->connector->get($this->namespaceClient->prefixPath($path), [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }
}
