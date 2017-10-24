<?php

namespace Kubernetes\Client\Factory;

use Kubernetes\Client\Adapter\Http\HttpPodStatusProvider;
use Kubernetes\Client\Model\PodStatusProvider;
use Kubernetes\Client\Repository\PodRepository;

class PodStatusProviderFactory
{
    public function createFromRepository(PodRepository $podRepository): PodStatusProvider
    {
        return new HttpPodStatusProvider($podRepository);
    }
}
