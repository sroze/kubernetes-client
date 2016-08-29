<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\DeploymentNotFound;
use Kubernetes\Client\Model\Deployment;

interface DeploymentRepository
{
    /**
     * Find a deployment by its name.
     *
     * @param string $name
     *
     * @throws DeploymentNotFound
     *
     * @return Deployment
     */
    public function findOneByName($name);

    /**
     * Create an Deployment object.
     *
     * @param Deployment $deployment
     *
     * @return Deployment
     */
    public function create(Deployment $deployment);

    /**
     * Update an Deployment object.
     *
     * @param Deployment $deployment
     *
     * @return Deployment
     */
    public function update(Deployment $deployment);

    /**
     * Check if the Deployment with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);
}
