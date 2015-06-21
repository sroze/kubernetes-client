<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\Connector;
use Kubernetes\Client\Model\NodeList;
use Kubernetes\Client\Repository\NodeRepository;

class HttpNodeRepository implements NodeRepository
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
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
