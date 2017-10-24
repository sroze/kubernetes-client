<?php

namespace spec\Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Adapter\Http\FileResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileHttpClientSpec extends ObjectBehavior
{
    function let(FileResolver $fileResolver)
    {
        $this->beConstructedWith($fileResolver);
    }

    function it_returns_the_file_content_for_async_request(FileResolver $fileResolver)
    {
        $fixtureFile = tempnam(sys_get_temp_dir(), 'spec');
        file_put_contents($fixtureFile, 'the content');

        $fileResolver->getFilePath(Argument::cetera())->willReturn($fixtureFile);

        $this->asyncRequest('GET', '/foo/bar/baz')->wait()->shouldBe('the content');
    }
}
