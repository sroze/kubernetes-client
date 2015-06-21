<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Model\NodeList;

interface NodeRepository
{
    /**
     * @return NodeList
     */
    public function findAll();
}
