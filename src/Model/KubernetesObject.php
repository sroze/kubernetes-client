<?php

namespace Kubernetes\Client\Model;

interface KubernetesObject
{
    /**
     * @return string
     */
    public function getKind();

    /**
     * @return ObjectMetadata
     */
    public function getMetadata();
}
