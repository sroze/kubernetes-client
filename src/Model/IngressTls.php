<?php

namespace Kubernetes\Client\Model;

class IngressTls
{
    /**
     * @var string
     */
    private $secretName;

    /**
     * @var string[]
     */
    private $hosts;

    /**
     * @param string $secretName
     * @param string[] $hosts
     */
    public function __construct($secretName, array $hosts = null)
    {
        $this->secretName = $secretName;
        $this->hosts = $hosts;
    }

    /**
     * @return string
     */
    public function getSecretName()
    {
        return $this->secretName;
    }

    /**
     * @return string[]|null
     */
    public function getHosts()
    {
        return $this->hosts;
    }
}
