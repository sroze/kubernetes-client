<?php

namespace spec\Kubernetes\Client\Adapter\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Kubernetes\Client\Adapter\Http\HttpClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuzzleHttpClientSpec extends ObjectBehavior
{
    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client, 'https://1.2.3.4', 'v1.5.2');
    }

    function it_is_an_http_client()
    {
        $this->shouldImplement(HttpClient::class);
    }

    function it_prefixies_the_request_with_the_base_url(ClientInterface $client)
    {
        $client->request('get', 'https://1.2.3.4/api/v1/namespaces', Argument::any())->shouldBeCalled()->willReturn(new Response());

        $this->request('get', '/api/v1/namespaces');
    }

    function it_returns_the_response_contents(ClientInterface $client)
    {
        $client->request('get', 'https://1.2.3.4/api/v1/namespaces', Argument::any())->shouldBeCalled()->willReturn(new Response(200, [], 'foo'));

        $this->request('get', '/api/v1/namespaces')->shouldReturn('foo');
    }

    function it_prefixies_the_asynchronous_request_with_the_base_url(ClientInterface $client)
    {
        $client->requestAsync('get', 'https://1.2.3.4/api/v1/namespaces', Argument::any())->shouldBeCalled()->willReturn(\GuzzleHttp\Promise\promise_for(new Response()));

        $this->asyncRequest('get', '/api/v1/namespaces');
    }

    function it_returns_the_request_body_for_the_asynchronous_request_with_the_base_url(ClientInterface $client)
    {
        $client->requestAsync('get', 'https://1.2.3.4/api/v1/namespaces', Argument::any())->shouldBeCalled()->willReturn(\GuzzleHttp\Promise\promise_for(new Response(200, [], 'foo')));

        $this->asyncRequest('get', '/api/v1/namespaces')->shouldReturnInTheFuture('foo');
    }

    public function getMatchers()
    {
        return [
            'returnInTheFuture' => function(PromiseInterface $promise, $value) {
                return $promise->wait() == $value;
            },
        ];
    }
}
