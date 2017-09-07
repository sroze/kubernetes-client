<?php

namespace Kubernetes\Client\Adapter\Http\Repository\RBAC;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ObjectNotFound;
use Kubernetes\Client\Model\RBAC\RoleBinding;
use Kubernetes\Client\Repository\RBAC\RoleBindingRepository;

class HttpRoleBindingRepository implements RoleBindingRepository
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @param HttpConnector       $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/rolebindings/%s', $name), 'rbac.authorization.k8s.io/v1beta1'), [
                'class' => RoleBinding::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new ObjectNotFound(sprintf(
                    'Role binding "%s" is not found',
                    $name
                ));
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(RoleBinding $binding)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/rolebindings', 'rbac.authorization.k8s.io/v1beta1'), $binding, [
            'class' => RoleBinding::class,
        ]);
    }
}
