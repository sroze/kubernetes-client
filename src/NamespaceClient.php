<?php

namespace Kubernetes\Client;

use Kubernetes\Client\Repository\PodRepository;
use Kubernetes\Client\Repository\ReplicationControllerRepository;
use Kubernetes\Client\Repository\ServiceRepository;

interface NamespaceClient
{
    /**
     * @return PodRepository
     */
    public function getPodRepository();

    /**
     * @return ServiceRepository
     */
    public function getServiceRepository();

    /**
     * @return ReplicationControllerRepository
     */
    public function getReplicationControllerRepository();
}
