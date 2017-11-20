<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicy;

interface NetworkPolicyRepository
{
    /**
     * @param string $name
     *
     * @throws ObjectNotFound
     *
     * @return NetworkPolicy
     */
    public function findByName($name);

    /**
     * @param NetworkPolicy $networkPolicy
     *
     * @throws ObjectNotFound
     *
     * @return NetworkPolicy
     */
    public function update(NetworkPolicy $networkPolicy);

    /**
     * @param NetworkPolicy $networkPolicy
     *
     * @return NetworkPolicy
     */
    public function create(NetworkPolicy $networkPolicy);
}
