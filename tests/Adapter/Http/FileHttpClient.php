<?php

namespace Kubernetes\Client\Adapter\Http;

class FileHttpClient implements HttpClient
{
    /**
     * @var FileResolver
     */
    private $fileResolver;

    /**
     * @param FileResolver $fileResolver
     */
    public function __construct(FileResolver $fileResolver)
    {
        $this->fileResolver = $fileResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        $filePath = $this->fileResolver->getFilePath($method, $path, $body, $options);
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf(
                'The HTTP fixture file "%s" do not exists',
                $filePath
            ));
        }

        return file_get_contents($filePath);
    }
}
