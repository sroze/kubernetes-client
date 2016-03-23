<?php

namespace Kubernetes\Client\Adapter\Http;

class FileResolver
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @param string $directory
     */
    public function __construct($directory = null)
    {
        $this->directory = $directory ?: __DIR__.'/fixtures';
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $body
     * @param array $options
     *
     * @return string
     */
    public function getFilePath($method, $path, $body = null, array $options = [])
    {
        $identifier = $method.'_'.$path;

        if (!empty($body)) {
            $identifier .= '-'.md5($body);
        }

        return $this->directory.DIRECTORY_SEPARATOR.preg_replace('/[^_\-.a-z0-9]/i', '_', $identifier);
    }
}
