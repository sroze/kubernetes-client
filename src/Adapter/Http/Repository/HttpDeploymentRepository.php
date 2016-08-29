<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\DeploymentNotFound;
use Kubernetes\Client\Model\Deployment;
use Kubernetes\Client\Model\Ingress;
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
    public function findOneByName($name)
    {
        try {
            $url = $this->namespaceClient->prefixPath(sprintf('/deployments/%s', $name));
            $url = '/apis/extensions/v1beta1'.$url;

            return $this->connector->get($url, [
                'class' => Ingress::class,
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
        $url = $this->namespaceClient->prefixPath('/deployments');
        $url = '/apis/extensions/v1beta1'.$url;

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

        $url = $this->namespaceClient->prefixPath(sprintf('/deployments/%s', $name));
        $url = '/apis/extensions/v1beta1'.$url;

        return $this->connector->patch($url, $deployment, [
            'class' => Deployment::class,
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
