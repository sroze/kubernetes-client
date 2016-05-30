<?php

namespace Kubernetes\Client\Model;

class IngressTls
{
    /**
     * @var string
     */
    private $secretName;

    /**
     * @param string $secretName
     */
    public function __construct($secretName)
    {
        $this->secretName = $secretName;
    }

    /**
     * @return string
     */
    public function getSecretName()
    {
        return $this->secretName;
    }
}
