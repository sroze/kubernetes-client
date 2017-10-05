<?php

namespace Kubernetes\Client\Repository\RBAC;

use Kubernetes\Client\Exception\Exception;
use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\RBAC\RoleBinding;

interface RoleBindingRepository
{
    /**
     * Find a deployment by its name.
     *
     * @param string $name
     *
     * @throws ObjectNotFound
     * @throws Exception
     *
     * @return RoleBinding
     */
    public function findOneByName($name);

    /**
     * Create an RoleBinding object.
     *
     * @param RoleBinding $binding
     *
     * @throws Exception
     *
     * @return RoleBinding
     */
    public function create(RoleBinding $binding);
}
