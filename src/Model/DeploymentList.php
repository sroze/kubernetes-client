<?php

namespace Kubernetes\Client\Model;

class DeploymentList implements \IteratorAggregate
{
    /**
     * @var Deployment[]
     */
    private $items = [];

    /**
     * @param Deployment[] $deployments
     *
     * @return DeploymentList
     */
    public static function fromDeployments(array $deployments)
    {
        $list = new self();
        $list->items = $deployments;

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getDeployments());
    }

    /**
     * @return Deployment[]
     */
    public function getDeployments()
    {
        return $this->items ?: [];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getDeployments());
    }
}
