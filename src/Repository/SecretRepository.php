<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\SecretNotFound;
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

    /**
     * Find a secret by its name.
     *
     * @param string $name
     *
     * @throws SecretNotFound
     *
     * @return Secret
     */
    public function findOneByName($name);

    /**
     * Return true if a secret with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);
}
