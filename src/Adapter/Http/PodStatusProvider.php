<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodAwareStatusProvider;
use Kubernetes\Client\Model\PodStatus;
use Kubernetes\Client\Repository\PodRepository;

/**
 * Provide pod status using a pod repository to fetch actual data.
 */
class PodStatusProvider implements PodAwareStatusProvider
{
    /**
     * @var Pod
     */
    private $pod;

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
    public function setPod(Pod $pod)
    {
        $this->pod = $pod;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isTerminated()
    {
        $pod = $this->reloadPod();

        $containerStatuses = $pod->getStatus()->getContainerStatuses();
        $terminatedContainers = array_filter($containerStatuses, function (ContainerStatus $containerStatus) {
            return null != $containerStatus->getState()->getTerminated();
        });

        return count($terminatedContainers) == count($containerStatuses);
    }

    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        $pod = $this->reloadPod();

        $podStatus = $pod->getStatus();

        return $podStatus->getPhase() == PodStatus::PHASE_PENDING;
    }

    /**
     * @return Pod
     *
     * @throws PodNotFound
     */
    protected function reloadPod(): Pod
    {
        if (empty($this->pod)) {
            throw new PodNotFound();
        }

        return $this->podRepository->findOneByName($this->pod->getMetadata()->getName());
    }
}
