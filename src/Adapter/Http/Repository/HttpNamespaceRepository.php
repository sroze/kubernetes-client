<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Exception\NamespaceNotFound;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\NamespaceList;
use Kubernetes\Client\Model\Status;
use Kubernetes\Client\Repository\NamespaceRepository;

class HttpNamespaceRepository implements NamespaceRepository
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @param HttpConnector $connector
     */
    public function __construct(HttpConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get('/namespaces', [
            'class' => NamespaceList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(KubernetesNamespace $namespace)
    {
        return $this->connector->post('/namespaces', $namespace, [
            'class' => KubernetesNamespace::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        $namespaces = $this->connector->get('/namespaces', [
            'class' => NamespaceList::class,
        ]);

        foreach ($namespaces as $namespace) {
            /** @var KubernetesNamespace $namespace */
            if ($namespace->getMetadata()->getName() == $name) {
                return $namespace;
            }
        }

        throw new NamespaceNotFound(sprintf(
            'Namespace with name "%s" is not found',
            $name
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function delete(KubernetesNamespace $namespace)
    {
        return $this->connector->delete(sprintf('/namespaces/%s', $namespace->getMetadata()->getName()), null, [
            'class' => KubernetesNamespace::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (NamespaceNotFound $e) {
            return false;
        }

        return true;
    }
}
