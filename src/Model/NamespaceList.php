<?php

namespace Kubernetes\Client\Model;

class NamespaceList implements \IteratorAggregate
{
    /**
     * @var KubernetesNamespace[]
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
     * @return KubernetesNamespace[]
     */
    public function getNamespaces()
    {
        return $this->items;
    }
}
