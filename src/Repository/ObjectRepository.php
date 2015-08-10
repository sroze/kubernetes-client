<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\KubernetesObject;

interface ObjectRepository
{
    /**
     * Find a kubernetes object by its name.
     *
     * @param string $name
     *
     * @throws ObjectNotFound
     *
     * @return KubernetesObject
     */
    public function findOneByName($name);

    /**
     * Create a kubernetes object.
     *
     * @param KubernetesObject $object
     *
     * @return KubernetesObject
     */
    public function create(KubernetesObject $object);

    /**
     * Updates a kubernetes object.
     *
     * @param KubernetesObject $object
     *
     * @return KubernetesObject
     */
    public function update(KubernetesObject $object);

    /**
     * Check if the object with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);
}
