<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Model\EventList;
use Kubernetes\Client\Model\KubernetesObject;

interface EventRepository
{
    /**
     * @param KubernetesObject $object
     *
     * @return EventList
     */
    public function findByObject(KubernetesObject $object);
}
