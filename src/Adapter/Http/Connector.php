<?php

namespace Kubernetes\Client\Adapter\Http;

interface Connector
{
    /**
     * @param string $path
     * @param array  $options
     *
     * @return mixed
     */
    public function get($path, array $options = []);

    /**
     * @param string $path
     * @param mixed  $body
     * @param array  $options
     *
     * @return mixed
     */
    public function post($path, $body, array $options = []);

    /**
     * @param string $path
     * @param mixed  $body
     * @param array  $options
     *
     * @return mixed
     */
    public function put($path, $body, array $options = []);

    /**
     * @param string $path
     * @param mixed  $body
     * @param array  $options
     *
     * @return mixed
     */
    public function patch($path, $body, array $options = []);

    /**
     * @param string $path
     * @param mixed  $body
     * @param array  $options
     *
     * @return mixed
     */
    public function delete($path, $body, array $options = []);
}
