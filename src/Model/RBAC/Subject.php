<?php

namespace Kubernetes\Client\Model\RBAC;

class Subject
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
    private $name;
    /**
     * @var string|null
     */
    private $apiVersion;

    /**
     * @param string $kind
     * @param string $namespace
     * @param string $name
     * @param string|null $apiVersion
     */
    public function __construct(string $kind, string $namespace, string $name, string $apiVersion = null)
    {
        $this->kind = $kind;
        $this->namespace = $namespace;
        $this->name = $name;
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }
}
