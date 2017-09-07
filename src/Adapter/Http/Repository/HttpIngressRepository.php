<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\IngressNotFound;
use Kubernetes\Client\Model\Ingress;
use Kubernetes\Client\Model\IngressList;
use Kubernetes\Client\Model\KeyValueObjectList;
use Kubernetes\Client\Model\ObjectMetadata;
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
    public function asyncFindAll() : PromiseInterface
    {
        $url = $this->namespaceClient->prefixPath('/ingresses', 'extensions/v1beta1');

        return $this->connector->asyncGet($url, [
            'class' => IngressList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            $url = $this->namespaceClient->prefixPath(sprintf('/ingresses/%s', $name), 'extensions/v1beta1');

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
    public function findByLabels(array $labels)
    {
        $labelSelector = HttpAdapter::createLabelSelector($labels);
        $url = $this->namespaceClient->prefixPath('/ingresses?labelSelector='.$labelSelector, 'extensions/v1beta1');

        return $this->connector->get($url, [
            'class' => IngressList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Ingress $ingress)
    {
        $url = $this->namespaceClient->prefixPath('/ingresses', 'extensions/v1beta1');

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

        $url = $this->namespaceClient->prefixPath(sprintf('/ingresses/%s', $ingressName), 'extensions/v1beta1');

        return $this->connector->patch($url, $ingress, [
            'class' => Ingress::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function annotate(string $name, KeyValueObjectList $annotations)
    {
        $currentIngress= $this->findOneByName($name);
        foreach ($annotations as $annotation) {
            $currentIngress->getMetadata()->getAnnotationList()->add($annotation);
        }

        $onlyAnnotationsIngress = new Ingress(new ObjectMetadata(
            $name,
            null,
            $currentIngress->getMetadata()->getAnnotationList()
        ));

        $url = $this->namespaceClient->prefixPath(sprintf('/ingresses/%s', $name), 'extensions/v1beta1');

        return $this->connector->patch($url, $onlyAnnotationsIngress, [
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
