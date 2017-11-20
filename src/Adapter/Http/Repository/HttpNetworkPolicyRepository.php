<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicy;
use Kubernetes\Client\Repository\NetworkPolicyRepository;

class HttpNetworkPolicyRepository implements NetworkPolicyRepository
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
        $url = $this->namespaceClient->prefixPath(sprintf('/networkpolicies/%s', $name), 'extensions/v1beta1');

        try {
            return $this->connector->get($url, [
                'class' => NetworkPolicy::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ObjectNotFound();
            }

            throw $e;
        }
    }

    /**
     *{@inheritdoc}
     */
    public function update(NetworkPolicy $networkPolicy)
    {
        $path = sprintf('/networkpolicies/%s', $networkPolicy->getMetadata()->getName(), 'extensions/v1beta1');

        return $this->connector->put($this->namespaceClient->prefixPath($path), $networkPolicy, [
            'class' => NetworkPolicy::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(NetworkPolicy $networkPolicy)
    {
        $url = $this->namespaceClient->prefixPath('/networkpolicies', 'extensions/v1beta1');

        return $this->connector->post($url, $networkPolicy, [
            'class' => NetworkPolicy::class,
        ]);
    }
}
