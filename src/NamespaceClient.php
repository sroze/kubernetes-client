<?php

namespace Kubernetes\Client;

use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Repository\CronJobRepository;
use Kubernetes\Client\Model\PodStatusProvider;
use Kubernetes\Client\Repository\DeploymentRepository;
use Kubernetes\Client\Repository\EventRepository;
use Kubernetes\Client\Repository\IngressRepository;
use Kubernetes\Client\Repository\NetworkPolicyRepository;
use Kubernetes\Client\Repository\JobRepository;
use Kubernetes\Client\Repository\PersistentVolumeClaimRepository;
use Kubernetes\Client\Repository\PodRepository;
use Kubernetes\Client\Repository\RBAC\RoleBindingRepository;
use Kubernetes\Client\Repository\ReplicationControllerRepository;
use Kubernetes\Client\Repository\SecretRepository;
use Kubernetes\Client\Repository\ServiceAccountRepository;
use Kubernetes\Client\Repository\ServiceRepository;

interface NamespaceClient
{
    /**
     * @return PodRepository
     */
    public function getPodRepository();

    /**
     * @return JobRepository
     */
    public function getJobRepository();

    /**
     * @return CronJobRepository
     */
    public function getCronJobRepository();

    /**
     * @return PodStatusProvider
     */
    public function getPodStatusProvider();

    /**
     * @return ServiceRepository
     */
    public function getServiceRepository();

    /**
     * @return ReplicationControllerRepository
     */
    public function getReplicationControllerRepository();

    /**
     * @return SecretRepository
     */
    public function getSecretRepository();

    /**
     * @return ServiceAccountRepository
     */
    public function getServiceAccountRepository();

    /**
     * @return PersistentVolumeClaimRepository
     */
    public function getPersistentVolumeClaimRepository();

    /**
     * @return IngressRepository
     */
    public function getIngressRepository();

    /**
     * @return DeploymentRepository
     */
    public function getDeploymentRepository();

    /**
     * @return EventRepository
     */
    public function getEventRepository();

    /**
     * @return RoleBindingRepository
     */
    public function getRoleBindingRepository();

    /**
     * @return NetworkPolicyRepository
     */
    public function getNetworkPolicyRepository();

    /**
     * @return KubernetesNamespace
     */
    public function getNamespace();
}
