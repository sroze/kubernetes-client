<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\AdapterInterface;
use Kubernetes\Client\Adapter\Http\Repository\HttpNamespaceRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpNodeRepository;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Repository\NamespaceRepository;
use Kubernetes\Client\Repository\NodeRepository;

class HttpAdapter implements AdapterInterface
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

    public function getNamespaceClient(KubernetesNamespace $namespace)
    {
        return new HttpNamespaceClient($this->connector, $namespace);
    }

    /**
     * @return NodeRepository
     */
    public function getNodeRepository()
    {
        return new HttpNodeRepository($this->connector);
    }

    /**
     * @return NamespaceRepository
     */
    public function getNamespaceRepository()
    {
        return new HttpNamespaceRepository($this->connector);
    }
}
