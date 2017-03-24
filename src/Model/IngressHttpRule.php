<?php

namespace Kubernetes\Client\Model;

class IngressHttpRule
{
    /**
     * @var IngressHttpRulePath[]
     */
    private $paths;

    /**
     * @param IngressHttpRulePath[] $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @return IngressHttpRulePath[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
