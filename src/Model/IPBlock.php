<?php

namespace Kubernetes\Client\Model;

class IPBlock
{
    /**
     * @var string|null
     */
    private $cidr;

    /**
     * @var string[]
     */
    private $except;

    /**
     * @param null|string $cidr
     * @param string[] $except
     */
    public function __construct($cidr, array $except = [])
    {
        $this->cidr = $cidr;
        $this->except = $except;
    }

    /**
     * @return string|null
     */
    public function getCidr()
    {
        return $this->cidr;
    }

    /**
     * @return string[]
     */
    public function getExcept(): array
    {
        return $this->except ?: [];
    }
}
