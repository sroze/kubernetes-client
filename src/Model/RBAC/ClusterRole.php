<?php

namespace Kubernetes\Client\Model\RBAC;

class ClusterRole extends Role
{
    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'ClusterRole';
    }
}
