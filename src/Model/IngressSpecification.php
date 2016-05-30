<?php

namespace Kubernetes\Client\Model;

class IngressSpecification
{
    /**
     * @var IngressBackend
     */
    private $backend;

    /**
     * @var IngressTls[]
     */
    private $tls;

    /**
     * @param IngressBackend $backend
     * @param IngressTls[]   $tls
     */
    public function __construct(IngressBackend $backend, array $tls = [])
    {
        $this->backend = $backend;
        $this->tls = $tls;
    }

    /**
     * @return IngressBackend
     */
    public function getBackend()
    {
        return $this->backend;
    }

    /**
     * @return IngressTls[]
     */
    public function getTls()
    {
        return $this->tls;
    }
}
