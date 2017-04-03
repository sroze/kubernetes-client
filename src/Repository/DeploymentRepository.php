<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\DeploymentNotFound;
use Kubernetes\Client\Model\Deployment;
use Kubernetes\Client\Model\DeploymentList;

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
     * Find all the deployments.
     *
     * @return DeploymentList
     */
    public function findAll();

    /**
     * Find all the deployments
     * Should return Kubernetes\Client\Model\DeploymentList once the promise is resolved
     *
     * @return PromiseInterface
     */
    public function asyncFindAll();

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

    /**
     * Rollback the given deployment.
     *
     * @param Deployment\DeploymentRollback $deploymentRollback
     *
     * @throws DeploymentNotFound
     *
     * @return Deployment\DeploymentRollback
     */
    public function rollback(Deployment\DeploymentRollback $deploymentRollback);
}
