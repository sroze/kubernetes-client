<?php

namespace spec\Kubernetes\Client;

use Kubernetes\Client\Adapter\AdapterInterface;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\NamespaceClient;
use Kubernetes\Client\Repository\NamespaceRepository;
use Kubernetes\Client\Repository\NodeRepository;
use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_returns_adapter_node_repository(AdapterInterface $adapter, NodeRepository $nodeRepository)
    {
        $adapter->getNodeRepository()->shouldBeCalled()->willReturn($nodeRepository);
        $this->getNodeRepository()->shouldReturn($nodeRepository);
    }

    public function it_returns_adapter_namespace_repository(AdapterInterface $adapter, NamespaceRepository $namespaceRepository)
    {
        $adapter->getNamespaceRepository()->shouldBeCalled()->willReturn($namespaceRepository);
        $this->getNamespaceRepository()->shouldReturn($namespaceRepository);
    }

    public function it_returns_namespace_client_from_adapter(AdapterInterface $adapter, KubernetesNamespace $namespace, NamespaceClient $namespaceClient)
    {
        $adapter->getNamespaceClient($namespace)->shouldBeCalled()->willReturn($namespaceClient);
        $this->getNamespaceClient($namespace)->shouldReturn($namespaceClient);
    }
}
