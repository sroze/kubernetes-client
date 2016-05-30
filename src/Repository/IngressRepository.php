<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\IngressNotFound;
use Kubernetes\Client\Model\Ingress;

interface IngressRepository
{
    /**
     * Find an ingress by its name.
     *
     * @param string $name
     *
     * @throws IngressNotFound
     *
     * @return Ingress
     */
    public function findOneByName($name);

    /**
     * Create an ingress object.
     *
     * @param Ingress $ingress
     *
     * @return Ingress
     */
    public function create(Ingress $ingress);

    /**
     * Update an ingress object.
     *
     * @param Ingress $ingress
     *
     * @return Ingress
     */
    public function update(Ingress $ingress);

    /**
     * Check if the ingress with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);
}
