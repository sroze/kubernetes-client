<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\CronJobNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\CronJob;
use Kubernetes\Client\Model\CronJobList;
use Kubernetes\Client\Model\CronJobStatus;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\CronJobRepository;

/**
 * Class HttpCronJobRepository
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class HttpCronJobRepository implements CronJobRepository
{
    const API = 'batch/v1beta';

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
        return $this->connector->get($this->namespaceClient->prefixPath('/cronjobs', self::API), [
            'class' => CronJobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncFindAll() : PromiseInterface
    {
        return $this->connector->asyncGet($this->namespaceClient->prefixPath('/cronjobs', self::API), [
            'class' => CronJobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = HttpAdapter::createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/cronjobs?labelSelector='.$labelSelector, self::API);

        return $this->connector->get($path, [
            'class' => CronJobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(CronJob $cronJob)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/cronjobs', self::API), $cronJob, [
            'class' => CronJob::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(CronJob $cronJob)
    {
        $path = sprintf('/cronjobs/%s', $cronJob->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path, self::API), $cronJob, [
            'class' => CronJob::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/cronjobs/%s', $name), self::API), [
                'class' => CronJob::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new CronJobNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CronJob $cronJob)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/cronjobs/%s', $cronJob->getMetadata()->getName()), self::API);

            return $this->connector->delete($path, null, [
                'class' => CronJob::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new CronJobNotFound();
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

        $path = '/cronjobs?labelSelector='.urlencode($labelSelector);

        return $this->connector->get($this->namespaceClient->prefixPath($path, self::API), [
            'class' => CronJobList::class,
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
        } catch (CronJobNotFound $e) {
            return false;
        }

        return true;
    }
}
