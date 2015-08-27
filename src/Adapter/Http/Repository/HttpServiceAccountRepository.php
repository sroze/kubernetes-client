<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ServiceAccountNotFound;
use Kubernetes\Client\Model\ServiceAccount;
use Kubernetes\Client\Repository\ServiceAccountRepository;

class HttpServiceAccountRepository implements ServiceAccountRepository
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
    public function findByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/serviceaccounts/%s', $name)), [
                'class' => ServiceAccount::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ServiceAccountNotFound();
            }

            throw $e;
        }
    }

    /**
     *{@inheritdoc}
     */
    public function update(ServiceAccount $serviceAccount)
    {
        $path = sprintf('/serviceaccounts/%s', $serviceAccount->getMetadata()->getName());

        return $this->connector->put($this->namespaceClient->prefixPath($path), $serviceAccount, [
            'class' => ServiceAccount::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(ServiceAccount $serviceAccount)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/serviceaccounts'), $serviceAccount, [
            'class' => ServiceAccount::class
        ]);
    }
}
