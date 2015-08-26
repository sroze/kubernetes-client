<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\NamespaceNotFound;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\NamespaceList;

interface NamespaceRepository
{
    /**
     * @return NamespaceList
     */
    public function findAll();

    /**
     * @param string $name
     *
     * @throws NamespaceNotFound
     *
     * @return KubernetesNamespace
     */
    public function findOneByName($name);

    /**
     * Check if a service with this name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param KubernetesNamespace $namespace
     *
     * @return KubernetesNamespace
     */
    public function create(KubernetesNamespace $namespace);
}
