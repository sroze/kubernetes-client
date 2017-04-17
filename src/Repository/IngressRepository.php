<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\IngressNotFound;
use Kubernetes\Client\Model\Ingress;
use Kubernetes\Client\Model\IngressList;
use Kubernetes\Client\Model\KeyValueObjectList;

interface IngressRepository
{
    /**
     * Will return an `IngressList` object.
     *
     * @return PromiseInterface
     */
    public function asyncFindAll() : PromiseInterface;

    /**
     * Find an ingress by its name.
     *
     * @param string $name
     *
     * @throws IngressNotFound
     *
     * @return Ingress
     */
    public function findOneByName($name);

    /**
     * Find ingresses by labels.
     *
     * @param array $labels
     *
     * @return IngressList
     */
    public function findByLabels(array $labels);

    /**
     * Create an ingress object.
     *
     * @param Ingress $ingress
     *
     * @return Ingress
     */
    public function create(Ingress $ingress);

    /**
     * Update an ingress object.
     *
     * @param Ingress $ingress
     *
     * @return Ingress
     */
    public function update(Ingress $ingress);

    /**
     * Check if the ingress with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param string $name
     * @param KeyValueObjectList $annotations
     *
     * @return Ingress
     */
    public function annotate(string $name, KeyValueObjectList $annotations);
}
