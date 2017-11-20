<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\Repository\HttpDeploymentRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpEventRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpIngressRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpNetworkPolicyRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpPersistentVolumeClaimRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpPodRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpReplicationControllerRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpSecretRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpServiceAccountRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpServiceRepository;
use Kubernetes\Client\Adapter\Http\Repository\RBAC\HttpRoleBindingRepository;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\NamespaceClient;
use Kubernetes\Client\Repository\NetworkPolicyRepository;

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
     * {@inheritdoc}
     */
    public function getIngressRepository()
    {
        return new HttpIngressRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeploymentRepository()
    {
        return new HttpDeploymentRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getEventRepository()
    {
        return new HttpEventRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleBindingRepository()
    {
        return new HttpRoleBindingRepository($this->connector, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getNetworkPolicyRepository()
    {
        return new HttpNetworkPolicyRepository($this->connector, $this);
    }

    /**
     * @param string $path
     * @param string|null $api
     *
     * Use the second argument to talk to a specific API, for example:
     * - rbac.authorization.k8s.io/v1beta1
     * - extensions/v1beta1
     *
     * @return string
     */
    public function prefixPath(string $path, string $api = null)
    {
        $url = sprintf('/namespaces/%s%s', $this->namespace->getMetadata()->getName(), $path);

        if ($api !== null) {
            $url = '/apis/'.$api.$url;
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
