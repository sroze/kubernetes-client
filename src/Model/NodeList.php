<?php

namespace Kubernetes\Client\Model;

class NodeList implements \IteratorAggregate
{
    /**
     * @var Node[]
     */
    private $items = [];

    /**
     * @param Node[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->items;
    }
}
