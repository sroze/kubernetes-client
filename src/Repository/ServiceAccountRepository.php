<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\ServiceAccountNotFound;
use Kubernetes\Client\Model\ServiceAccount;

interface ServiceAccountRepository
{
    /**
     * Find a service account by its name.
     *
     * @param string $name
     *
     * @throws ServiceAccountNotFound
     *
     * @return ServiceAccount
     */
    public function findByName($name);

    /**
     * @param ServiceAccount $serviceAccount
     *
     * @throws ServiceAccountNotFound
     *
     * @return ServiceAccount
     */
    public function update(ServiceAccount $serviceAccount);

    /**
     * @param ServiceAccount $serviceAccount
     *
     * @return ServiceAccount
     */
    public function create(ServiceAccount $serviceAccount);
}
