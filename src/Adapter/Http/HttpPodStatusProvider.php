<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodStatusProvider;
use Kubernetes\Client\Model\PodStatus;
use Kubernetes\Client\Repository\PodRepository;

/**
 * Provide pod status using a pod repository to fetch actual data.
 */
class HttpPodStatusProvider implements PodStatusProvider
{
    /**
     * @var PodRepository
     */
    private $podRepository;

    public function __construct(PodRepository $podRepository)
    {
        $this->podRepository = $podRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function isTerminated(Pod $pod)
    {
        $pod = $this->reloadPod($pod);

        $containerStatuses = $pod->getStatus()->getContainerStatuses();
        $terminatedContainers = array_filter($containerStatuses, function (ContainerStatus $containerStatus) {
            return null != $containerStatus->getState()->getTerminated();
        });

        return count($terminatedContainers) == count($containerStatuses);
    }

    /**
     * {@inheritdoc}
     */
    public function isPending(Pod $pod)
    {
        $pod = $this->reloadPod($pod);

        $podStatus = $pod->getStatus();

        return $podStatus->getPhase() == PodStatus::PHASE_PENDING;
    }

    /**
     * Return an up to date instance of the given pod.
     *
     * @param Pod $pod
     * @return Pod
     *
     * @throws PodNotFound
     */
    protected function reloadPod(Pod $pod): Pod
    {
        return $this->podRepository->findOneByName($pod->getMetadata()->getName());
    }
}
