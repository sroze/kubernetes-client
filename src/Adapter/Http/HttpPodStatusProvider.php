<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\React\PodOutputStream;
use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodStatusProvider;
use Kubernetes\Client\Model\PodStatus;
use Kubernetes\Client\Repository\PodRepository;
use React\EventLoop\LoopInterface;
use React\Stream\ReadableStreamInterface;

/**
 * Provide pod status using a pod repository to fetch actual data.
 */
class HttpPodStatusProvider implements PodStatusProvider
{
    /**
     * @var PodRepository
     */
    private $podRepository;

    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @var int
     */
    private $pollingInterval;

    /**
     * Constructor.
     *
     * @param PodRepository $podRepository
     * @param HttpConnector $connector
     * @param HttpNamespaceClient $namespaceClient
     * @param float $pollingInterval Time between HTTP requests in seconds.
     */
    public function __construct(
        PodRepository $podRepository,
        HttpConnector $connector,
        HttpNamespaceClient $namespaceClient,
        float $pollingInterval = 0.5
    ) {
        $this->podRepository = $podRepository;
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
        $this->pollingInterval = $pollingInterval;
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
     * {@inheritdoc}
     */
    public function attach(Pod $pod, callable $callable)
    {
        while ($this->isPending($pod)) {
            usleep($this->pollingInterval * 1000000);
        }

        $logCursor = 0;
        while (!$this->isTerminated($pod)) {
            $logCursor = $this->streamLogs($pod, $callable, $logCursor);

            usleep($this->pollingInterval * 1000000);
        }

        $this->streamLogs($pod, $callable, $logCursor);

        $pod = $this->podRepository->findOneByName($pod->getMetadata()->getName());

        return $pod;
    }

    /**
     * {@inheritdoc}
     */
    public function streamOutput(Pod $pod, LoopInterface $loop): ReadableStreamInterface
    {
        return new PodOutputStream(
            $pod,
            $loop,
            $this->connector,
            $this->namespaceClient,
            $this,
            $this->pollingInterval
        );
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

    /**
     * Fetch the standard output of the pod and pass it to the callback function.
     *
     * @param Pod $pod
     * @param callable $callable
     * @param string $cursor
     * @return string Updated cursor.
     */
    private function streamLogs(Pod $pod, callable $callable, $cursor)
    {
        $logs = $this->getLogs($pod);
        $incrementalLogs = substr($logs, $cursor);

        if (!empty($incrementalLogs)) {
            $cursor += strlen($incrementalLogs);

            $callable($incrementalLogs);
        }

        return $cursor;
    }

    /**
     * Get pod's logs.
     *
     * @param Pod $pod
     *
     * @return string
     */
    private function getLogs(Pod $pod)
    {
        $name = $pod->getMetadata()->getName();

        return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/pods/%s/log', $name)));
    }
}
