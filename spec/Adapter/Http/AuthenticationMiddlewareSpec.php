<?php

namespace spec\Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\AuthenticationMiddleware;
use Kubernetes\Client\Adapter\Http\HttpClient;
use PhpSpec\ObjectBehavior;

/**
 * @mixin AuthenticationMiddleware
 */
class AuthenticationMiddlewareSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient, 'username', 'password');
    }

    function it_can_do_an_authenticated_request(HttpClient $httpClient)
    {
        $this->request('get', 'path/', '?body=something', []);

        $httpClient->request(
            'get',
            'path/',
            '?body=something',
            [ 'headers' => ['Authorization' => 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=',]]
        )->shouldHaveBeenCalled();
    }

    function it_do_an_authenticated_request_asynchronously(HttpClient $httpClient)
    {
        $this->asyncRequest('get', 'path/', '?body=something', []);

        $httpClient->asyncRequest(
            'get',
            'path/',
            '?body=something',
            [ 'headers' => ['Authorization' => 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=',]]
        )->shouldHaveBeenCalled();
    }
}
