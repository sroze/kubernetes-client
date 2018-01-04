<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\JobNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Job;
use Kubernetes\Client\Model\JobList;
use Kubernetes\Client\Model\JobStatus;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\JobRepository;

/**
 * Class HttpJobRepository
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class HttpJobRepository implements JobRepository
{
    const API = 'batch/v1';
    
    /**
     * Interval of the attach loop in case of using the "legacy" implementation.
     *
     * @var int
     */
    const ATTACH_LOOP_INTERVAL = 500;

    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @param HttpConnector       $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get($this->namespaceClient->prefixPath('/jobs', self::API), [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncFindAll() : PromiseInterface
    {
        return $this->connector->asyncGet($this->namespaceClient->prefixPath('/jobs', self::API), [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = HttpAdapter::createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/jobs?labelSelector='.$labelSelector, self::API);

        return $this->connector->get($path, [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Job $job)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/jobs', self::API), $job, [
            'class' => Job::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Job $job)
    {
        $path = sprintf('/jobs/%s', $job->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path, self::API), $job, [
            'class' => Job::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/jobs/%s', $name), self::API), [
                'class' => Job::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new JobNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Job $job)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/jobs/%s', $job->getMetadata()->getName()), self::API);

            return $this->connector->delete($path, null, [
                'class' => Job::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new JobNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByReplicationController(ReplicationController $replicationController)
    {
        $selector = $replicationController->getSpecification()->getSelector();
        $labelSelector = HttpAdapter::createLabelSelector($selector);

        $path = '/jobs?labelSelector='.urlencode($labelSelector);

        return $this->connector->get($this->namespaceClient->prefixPath($path, self::API), [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (JobNotFound $e) {
            return false;
        }

        return true;
    }
}
