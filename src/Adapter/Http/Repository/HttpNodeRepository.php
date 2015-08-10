<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Model\NodeList;
use Kubernetes\Client\Repository\NodeRepository;

class HttpNodeRepository implements NodeRepository
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @param Connector $connector
     */
    public function __construct(HttpConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get('/nodes', [
            'class' => NodeList::class,
        ]);
    }
}
