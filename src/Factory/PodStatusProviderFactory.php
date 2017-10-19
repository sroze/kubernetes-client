<?php

namespace Kubernetes\Client\Factory;

use Kubernetes\Client\Adapter\Http\PodStatusProvider;
use Kubernetes\Client\Model\PodAwareStatusProvider;
use Kubernetes\Client\Repository\PodRepository;

class PodStatusProviderFactory
{
    public function createFromRepository(PodRepository $podRepository): PodAwareStatusProvider
    {
        return new PodStatusProvider($podRepository);
    }
}
