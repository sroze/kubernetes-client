<?php

namespace Kubernetes\Client\Adapter;

use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\NamespaceClient;
use Kubernetes\Client\Repository\NamespaceRepository;
use Kubernetes\Client\Repository\NodeRepository;

interface AdapterInterface
{
    /**
     * @return NodeRepository
     */
    public function getNodeRepository();

    /**
     * @return NamespaceRepository
     */
    public function getNamespaceRepository();

    /**
     * @param KubernetesNamespace $namespace
     *
     * @return NamespaceClient
     */
    public function getNamespaceClient(KubernetesNamespace $namespace);
}
