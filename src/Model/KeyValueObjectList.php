<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Exception\ObjectNotFound;

class KeyValueObjectList implements \IteratorAggregate
{
    /**
     * @var KeyValueObject[]
     */
    private $items = [];

    /**
     * @param KeyValueObject[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param array  $array
     * @param string $className
     *
     * @return array
     */
    public static function fromAssociativeArray(array $array, $className = KeyValueObject::class)
    {
        $objects = [];
        foreach ($array as $name => $value) {
            $objects[] = new $className($name, $value);
        }

        return new self($objects);
    }

    /**
     * @param KeyValueObject $object
     */
    public function add(KeyValueObject $object)
    {
        if ($this->hasKey($object->getKey())) {
            $this->removeByKey($object->getKey());
        }

        $this->items[] = $object;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey($key)
    {
        return $this->indexByKey($key) !== false;
    }

    /**
     * @param string $key
     *
     * @throws ObjectNotFound
     */
    public function removeByKey($key)
    {
        if (false !== ($index = $this->indexByKey($key))) {
            array_splice($this->items, $index, 1);
        } else {
            throw new ObjectNotFound();
        }
    }

    /**
     * @return array
     */
    public function toAssociativeArray()
    {
        $objects = [];
        foreach ($this->items as $object) {
            $objects[$object->getKey()] = $object->getValue();
        }

        return $objects;
    }

    /**
     * @param string $key
     *
     * @return bool|int
     */
    private function indexByKey($key)
    {
        foreach ($this->items as $index => $item) {
            if ($item->getKey() === $key) {
                return $index;
            }
        }

        return false;
    }

    public function __clone()
    {
        $this->items = array_map(function (KeyValueObject $item) {
            return clone $item;
        }, $this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
