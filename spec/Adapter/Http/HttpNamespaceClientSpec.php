<?php

namespace spec\Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\ObjectMetadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpNamespaceClientSpec extends ObjectBehavior
{
    function let(HttpConnector $connector, KubernetesNamespace $namespace)
    {
        $this->beConstructedWith($connector, $namespace);
    }

    function it_prefixes_the_path_with_namespace_name(KubernetesNamespace $namespace)
    {
        $namespace->getMetadata()->willReturn(new ObjectMetadata('foo-bar'));

        $this->prefixPath('/pods')->shouldReturn('/namespaces/foo-bar/pods');
    }

    function it_prefixes_the_path_with_namespace_name_and_api(KubernetesNamespace $namespace)
    {
        $namespace->getMetadata()->willReturn(new ObjectMetadata('foo-bar'));

        $this->prefixPath('/deployments', 'extensions/v1beta1')->shouldReturn('/apis/extensions/v1beta1/namespaces/foo-bar/deployments');

    }
}
