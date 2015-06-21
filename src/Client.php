<?php

namespace Kubernetes\Client;

use Kubernetes\Client\Adapter\AdapterInterface;
use Kubernetes\Client\Repository\NamespaceRepository;
use Kubernetes\Client\Repository\NodeRepository;
use Kubernetes\Client\Model\KubernetesNamespace;

class Client implements AdapterInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespaceClient(KubernetesNamespace $namespace)
    {
        return $this->adapter->getNamespaceClient($namespace);
    }

    /**
     * @return NodeRepository
     */
    public function getNodeRepository()
    {
        return $this->adapter->getNodeRepository();
    }

    /**
     * @return NamespaceRepository
     */
    public function getNamespaceRepository()
    {
        return $this->adapter->getNamespaceRepository();
    }
}
