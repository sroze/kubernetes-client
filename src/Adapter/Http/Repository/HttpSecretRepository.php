<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\SecretNotFound;
use Kubernetes\Client\Model\Secret;
use Kubernetes\Client\Repository\SecretRepository;

class HttpSecretRepository implements SecretRepository
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
    public function create(Secret $secret)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/secrets'), $secret, [
            'class' => Secret::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Secret $secret)
    {
        $path = sprintf('/secrets/%s', $secret->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path), $secret, [
            'class' => Secret::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/secrets/%s', $name)), [
                'class' => Secret::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new SecretNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (SecretNotFound $e) {
            return false;
        }

        return true;
    }
}
