<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\IngressNotFound;
use Kubernetes\Client\Model\Ingress;
use Kubernetes\Client\Repository\IngressRepository;

class HttpIngressRepository implements IngressRepository
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
            $url = $this->namespaceClient->prefixPath(sprintf('/ingresses/%s', $name));
            $url = '/apis/extensions/v1beta1'.$url;

            return $this->connector->get($url, [
                'class' => Ingress::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new IngressNotFound(sprintf(
                    'Ingress named "%s" is not found',
                    $name
                ));
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(Ingress $ingress)
    {
        $url = $this->namespaceClient->prefixPath('/ingresses');
        $url = '/apis/extensions/v1beta1'.$url;

        return $this->connector->post($url, $ingress, [
            'class' => Ingress::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Ingress $ingress)
    {
        $ingressName = $ingress->getMetadata()->getName();
        $currentIngress = $this->findOneByName($ingressName);

        if ($ingress->getSpecification() == $currentIngress->getSpecification()) {
            return $currentIngress;
        }

        $url = $this->namespaceClient->prefixPath(sprintf('/ingresses/%s', $ingressName));
        $url = '/apis/extensions/v1beta1'.$url;

        return $this->connector->patch($url, $ingress, [
            'class' => Ingress::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (IngressNotFound $e) {
            return false;
        }

        return true;
    }
}
