<?php

namespace Kubernetes\Client\Model;

class ObjectReference extends LocalObjectReference
{
    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $uid;

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var string
     */
    private $resourceVersion;

    /**
     * @var string
     */
    private $fieldPath;
}
