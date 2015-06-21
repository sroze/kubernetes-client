<?php

namespace Kubernetes\Client\Exception;

class TooManyObjects extends \Exception
{
    protected $message = 'Expected one object but found many';

    /**
     * @var array
     */
    private $objects;

    public function __construct(array $objects = [])
    {
        $this->objects = $objects;
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }
}
