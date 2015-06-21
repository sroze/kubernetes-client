<?php

namespace Kubernetes\Client\Model;

class PodList implements \IteratorAggregate
{
    /**
     * @var Pod[]
     */
    private $items = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return Pod[]
     */
    public function getPods()
    {
        return $this->items;
    }
}
