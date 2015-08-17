<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Model\Secret;

interface SecretRepository
{
    /**
     * Create a new secret.
     *
     * @param Secret $secret
     *
     * @return Secret
     */
    public function create(Secret $secret);
}
