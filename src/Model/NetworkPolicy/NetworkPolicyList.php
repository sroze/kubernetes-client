<?php

namespace Kubernetes\Client\Model\NetworkPolicy;

class NetworkPolicyList implements \IteratorAggregate
{
    /**
     * @var NetworkPolicy[]
     */
    private $items = [];

    /**
     * @param NetworkPolicy[] $policies
     *
     * @return NetworkPolicyList
     */
    public static function fromItems(array $policies)
    {
        $list = new self();
        $list->items = $policies;

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
