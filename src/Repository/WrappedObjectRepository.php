<?php

namespace Kubernetes\Client\Repository;

use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\KubernetesObject;

class WrappedObjectRepository implements ObjectRepository
{
    /**
     * @var PodRepository|ReplicationControllerRepository|ServiceRepository
     */
    private $specificRepository;

    /**
     * @param PodRepository|ServiceRepository|ReplicationControllerRepository $specificRepository
     */
    public function __construct($specificRepository)
    {
        $this->specificRepository = $specificRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        return $this->specificRepository->findOneByName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function create(KubernetesObject $object)
    {
        return $this->specificRepository->create($object);
    }

    /**
     * {@inheritdoc}
     */
    public function update(KubernetesObject $object)
    {
        return $this->specificRepository->update($object);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);

            return true;
        } catch (ObjectNotFound $e) {
            return false;
        }
    }
}
