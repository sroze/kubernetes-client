<?php

namespace Kubernetes\Client\Model\RBAC;

class ClusterRoleBinding extends RoleBinding
{
    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'ClusterRoleBinding';
    }
}
