<?php

namespace Kubernetes\Client\Model\RBAC;

class RoleRef
{
    /**
     * @var string
     */
    private $apiGroup;

    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $apiGroup
     * @param string $kind
     * @param string $name
     */
    public function __construct(string $apiGroup, string $kind, string $name)
    {
        $this->apiGroup = $apiGroup;
        $this->kind = $kind;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getApiGroup(): string
    {
        return $this->apiGroup;
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
    public function getName(): string
    {
        return $this->name;
    }
}
