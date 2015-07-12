<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\Repository\HttpPodRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpReplicationControllerRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpServiceRepository;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\NamespaceClient;
use Kubernetes\Client\Repository\PodRepository;
use Kubernetes\Client\Repository\ReplicationControllerRepository;
use Kubernetes\Client\Repository\ServiceRepository;

class HttpNamespaceClient implements NamespaceClient
{
    /**
     * @var KubernetesNamespace
     */
    private $namespace;
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param Connector           $connector
     * @param KubernetesNamespace $namespace
     */
    public function __construct(Connector $connector, KubernetesNamespace $namespace)
    {
        $this->namespace = $namespace;
        $this->connector = $connector;
    }

    /**
     * @return PodRepository
     */
    public function getPodRepository()
    {
        return new HttpPodRepository($this->connector, $this);
    }

    /**
     * @return ServiceRepository
     */
    public function getServiceRepository()
    {
        return new HttpServiceRepository($this->connector, $this);
    }

    /**
     * @return ReplicationControllerRepository
     */
    public function getReplicationControllerRepository()
    {
        return new HttpReplicationControllerRepository($this->connector, $this);
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