<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\PersistentVolumeClaimNotFound;
use Kubernetes\Client\Model\PersistentVolumeClaim;

interface PersistentVolumeClaimRepository
{
    /**
     * Find a persistent volume claim by its name.
     *
     * @param string $name
     *
     * @throws PersistentVolumeClaimNotFound
     *
     * @return PersistentVolumeClaim
     */
    public function findOneByName($name);

    /**
     * Create a new persistent volume claim.
     *
     * @param PersistentVolumeClaim $claim
     *
     * @return PersistentVolumeClaim
     */
    public function create(PersistentVolumeClaim $claim);
}
