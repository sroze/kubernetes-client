<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\DeploymentNotFound;
use Kubernetes\Client\Model\Deployment;
use Kubernetes\Client\Model\DeploymentList;
use Kubernetes\Client\Repository\DeploymentRepository;

class HttpDeploymentRepository implements DeploymentRepository
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @param HttpConnector       $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        $url = $this->namespaceClient->prefixPath('/deployments', 'extensions/v1beta1');

        return $this->connector->get($url, [
            'class' => DeploymentList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncFindAll()
    {
        $url = $this->namespaceClient->prefixPath('/deployments', 'extensions/v1beta1');

        return $this->connector->asyncGet($url, [
            'class' => DeploymentList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            $url = $this->namespaceClient->prefixPath(sprintf('/deployments/%s', $name), 'extensions/v1beta1');

            return $this->connector->get($url, [
                'class' => Deployment::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new DeploymentNotFound(sprintf(
                    'Deployment named "%s" is not found',
                    $name
                ));
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(Deployment $deployment)
    {
        $url = $this->namespaceClient->prefixPath('/deployments', 'extensions/v1beta1');

        return $this->connector->post($url, $deployment, [
            'class' => Deployment::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Deployment $deployment)
    {
        $name = $deployment->getMetadata()->getName();
        $current = $this->findOneByName($name);

        if ($deployment->getSpecification() == $current->getSpecification()) {
            return $current;
        }

        $url = $this->namespaceClient->prefixPath(sprintf('/deployments/%s', $name), 'extensions/v1beta1');

        return $this->connector->patch($url, $deployment, [
            'class' => Deployment::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback(Deployment\DeploymentRollback $deploymentRollback)
    {
        $url = $this->namespaceClient->prefixPath(sprintf('/deployments/%s/rollback', $deploymentRollback->getName()), 'extensions/v1beta1');

        return $this->connector->post($url, $deploymentRollback, [
            'class' => Deployment\DeploymentRollback::class,
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (DeploymentNotFound $e) {
            return false;
        }

        return true;
    }
}
