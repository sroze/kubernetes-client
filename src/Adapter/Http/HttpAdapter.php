<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\AdapterInterface;
use Kubernetes\Client\Adapter\Http\Repository\HttpNamespaceRepository;
use Kubernetes\Client\Adapter\Http\Repository\HttpNodeRepository;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Repository\NamespaceRepository;
use Kubernetes\Client\Repository\NodeRepository;

class HttpAdapter implements AdapterInterface
{
    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @param HttpConnector $connector
     */
    public function __construct(HttpConnector $connector)
    {
        $this->connector = $connector;
    }

    public function getNamespaceClient(KubernetesNamespace $namespace)
    {
        return new HttpNamespaceClient($this->connector, $namespace);
    }

    /**
     * @return NodeRepository
     */
    public function getNodeRepository()
    {
        return new HttpNodeRepository($this->connector);
    }

    /**
     * @return NamespaceRepository
     */
    public function getNamespaceRepository()
    {
        return new HttpNamespaceRepository($this->connector);
    }

    /**
     * @param array|string $selector
     *
     * @return string
     */
    public static function createLabelSelector($selector)
    {
        if (is_array($selector)) {
            $matchingList = [];
            foreach ($selector as $key => $value) {
                $matchingList[] = $key.'='.$value;
            }

            $selector = implode(',', $matchingList);
        } elseif (!is_string($selector)) {
            throw new \RuntimeException('Selector do not have a valid type');
        }

        return $selector;
    }
}
