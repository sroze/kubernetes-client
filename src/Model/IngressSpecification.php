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
     * @var IngressRule[]
     */
    private $rules;

    /**
     * @param IngressBackend $backend
     * @param IngressTls[] $tls
     * @param IngressRule[] $rules
     */
    public function __construct(IngressBackend $backend = null, array $tls = [], array $rules = [])
    {
        $this->backend = $backend;
        $this->tls = $tls;
        $this->rules = $rules;
    }

    /**
     * @return IngressBackend|null
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

    /**
     * @return IngressRule[]
     */
    public function getRules(): array
    {
        return $this->rules ?: [];
    }
}
