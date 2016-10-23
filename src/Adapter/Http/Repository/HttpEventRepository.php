<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Model\EventList;
use Kubernetes\Client\Model\KubernetesObject;
use Kubernetes\Client\Repository\EventRepository;

class HttpEventRepository implements EventRepository
{
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
    public function findByObject(KubernetesObject $object)
    {
        $url = $this->namespaceClient->prefixPath('/events') . '?' . http_build_query([
            'fieldSelector' => http_build_query([
                'involvedObject.kind' => $object->getKind(),
                'involvedObject.name' => $object->getMetadata()->getName(),
            ], null, ',')
        ]);

        return $this->connector->get($url, [
            'class' => EventList::class,
        ]);
    }
}
