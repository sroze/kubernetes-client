<?php

namespace Kubernetes\Client\Model;

class IngressHttpRulePath
{
    /**
     * @var IngressBackend
     */
    private $backend;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @param IngressBackend $backend
     * @param null|string $path
     */
    public function __construct(IngressBackend $backend, string $path = null)
    {
        $this->backend = $backend;
        $this->path = $path;
    }

    /**
     * @return IngressBackend
     */
    public function getBackend(): IngressBackend
    {
        return $this->backend;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }
}
