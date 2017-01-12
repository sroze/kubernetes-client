<?php

namespace spec\Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\Repository\HttpDeploymentRepository;
use Kubernetes\Client\Model\Deployment;
use Kubernetes\Client\Model\ObjectMetadata;
use PhpSpec\ObjectBehavior;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

/**
 * @mixin HttpDeploymentRepository
 */
class HttpDeploymentRepositorySpec extends ObjectBehavior
{
    public function let(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->beConstructedWith($connector, $namespaceClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kubernetes\Client\Adapter\Http\Repository\HttpDeploymentRepository');
    }

    function it_can_find_all_deployments(
        HttpConnector       $connector,
        HttpNamespaceClient $namespaceClient,
        ResponseInterface   $response
    ) {
        $namespaceClient->prefixPath('/deployments')->shouldBeCalled()->willReturn('/something');

        $connector
            ->get('/apis/extensions/v1beta1/something', ['class' => 'Kubernetes\Client\Model\DeploymentList'])
            ->shouldBeCalled()
            ->willReturn([$response]);

        $this->findAll()->shouldBe([$response]);
    }

    function it_can_find_one_deployment_by_name(
        HttpConnector       $connector,
        HttpNamespaceClient $namespaceClient,
        ResponseInterface   $response
    ) {
        $namespaceClient->prefixPath('/deployments/deployment-name')->shouldBeCalled()->willReturn('/something');

        $connector
            ->get('/apis/extensions/v1beta1/something', ['class' => 'Kubernetes\Client\Model\Deployment'])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->findOneByName('deployment-name')->shouldBe($response);
    }

    function it_can_create_repository(
        HttpConnector       $connector,
        HttpNamespaceClient $namespaceClient,
        ResponseInterface   $response,
        Deployment $deployment
    ) {

        $namespaceClient->prefixPath('/deployments')->shouldBeCalled()->willReturn('/something');

        $connector
            ->post('/apis/extensions/v1beta1/something', $deployment, ['class' => 'Kubernetes\Client\Model\Deployment'])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->create($deployment)->shouldBe($response);
    }

    function it_can_update_repository(
        HttpConnector       $connector,
        HttpNamespaceClient $namespaceClient,
        Deployment          $deployment1,
        Deployment          $deployment2,
        ObjectMetadata      $deploymentMetadata
    ) {
        // ARRANGE

        $connector
            ->get('/apis/extensions/v1beta1/something', ['class' => 'Kubernetes\Client\Model\Deployment'])
            ->shouldBeCalled()
            ->willReturn($deployment2);

        $namespaceClient->prefixPath('/deployments/deployment-name')->shouldBeCalled()->willReturn('/something');

        $deployment1->getMetadata()->willReturn($deploymentMetadata);
        $deploymentMetadata->getName()->willReturn('deployment-name');

        $deployment1->getSpecification()->willReturn('spec1');
        $deployment2->getSpecification()->willReturn('spec2');

        // ASSERT
        $connector
            ->patch('/apis/extensions/v1beta1/something', $deployment1, ['class' => 'Kubernetes\Client\Model\Deployment'])
            ->shouldBeCalled()
            ->willReturn($deployment1);

        // ACT
        $this->update($deployment1);
    }

    function it_doesnt_update_repository_if_nothings_changed(
        HttpConnector       $connector,
        HttpNamespaceClient $namespaceClient,
        Deployment          $deployment1,
        Deployment          $deployment2,
        ObjectMetadata      $deploymentMetadata
    ) {
        // ARRANGE

        $connector
            ->get('/apis/extensions/v1beta1/something', ['class' => 'Kubernetes\Client\Model\Deployment'])
            ->shouldBeCalled()
            ->willReturn($deployment2);

        $namespaceClient->prefixPath('/deployments/deployment-name')->shouldBeCalled()->willReturn('/something');

        $deployment1->getMetadata()->willReturn($deploymentMetadata);
        $deploymentMetadata->getName()->willReturn('deployment-name');

        $deployment1->getSpecification()->willReturn('spec1');
        $deployment2->getSpecification()->willReturn('spec1');

        // ASSERT
        $connector
            ->patch(Argument::any(), Argument::any(), Argument::any())
            ->shouldNotBeCalled();

        // ACT
        $this->update($deployment1);
    }
}
