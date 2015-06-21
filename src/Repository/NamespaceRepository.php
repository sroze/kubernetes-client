<?php

namespace Kubernetes\Client\Repository;

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
     * @return KubernetesNamespace|null
     */
    public function findOneByName($name);

    /**
     * @param KubernetesNamespace $namespace
     *
     * @return KubernetesNamespace
     */
    public function create(KubernetesNamespace $namespace);
}
