<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\Repository\HttpPersistentVolumeClaimRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpPodRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpReplicationControllerRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpSecretRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpServiceAccountRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpServiceRepository;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\NamespaceClient;

class HttpNamespaceClient implements NamespaceClient
{
    /**
     * @var KubernetesNamespace
     */
    private $namespace;
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @param HttpConnector       $connector
     * @param KubernetesNamespace $namespace
     */
    public function __construct(HttpConnector $connector, KubernetesNamespace $namespace)
    {
        $this->namespace = $namespace;
        $this->connector = $connector;
    }

    /**
     * {@inheritdoc}
     */
    public function getPodRepository()
    {
        return new HttpPodRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceRepository()
    {
        return new HttpServiceRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getReplicationControllerRepository()
    {
        return new HttpReplicationControllerRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getSecretRepository()
    {
        return new HttpSecretRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceAccountRepository()
    {
        return new HttpServiceAccountRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentVolumeClaimRepository()
    {
        return new HttpPersistentVolumeClaimRepository($this->connector, $this);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function prefixPath($path)
    {
        return sprintf('/namespaces/%s%s', $this->namespace->getMetadata()->getName(), $path);
    }

    /**
     * @param array|string $selector
     *
     * @return string
     */
    public function createLabelSelector($selector)
    {
        if (is_array($selector)) {
            $matchingList = [];
            foreach ($selector as $key => $value) {
                $matchingList[] = $key.'='.$value;
            }

            $selector = implode(',', $matchingList);
        } elseif (!is_string($selector)) {
            throw new \RuntimeException('Selector do not have a valid type');
        }

        return $selector;
    }
}
