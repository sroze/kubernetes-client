<?php

namespace spec\Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\NamespaceClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpReplicationControllerRepositorySpec extends ObjectBehavior
{
    public function let(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->beConstructedWith($connector, $namespaceClient);
    }

    function it_calls_find_all_asynchronously(HttpConnector $connector, NamespaceClient $namespaceClient, PromiseInterface $promise)
    {
        $namespaceClient->prefixPath('/replicationcontrollers')->willReturn('/something');
        $connector->asyncGet('/something', Argument::type('array'))->willReturn($promise);

        $this->asyncFindAll()->shouldHaveType(PromiseInterface::class);
        $this->asyncFindAll()->shouldReturn($promise);
    }
}
