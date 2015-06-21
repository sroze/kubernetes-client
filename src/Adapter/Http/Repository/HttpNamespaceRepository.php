<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\Connector;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\NamespaceList;
use Kubernetes\Client\Repository\NamespaceRepository;

class HttpNamespaceRepository implements NamespaceRepository
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
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

        return;
    }
}
