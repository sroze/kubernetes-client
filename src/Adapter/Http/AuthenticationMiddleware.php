<?php

namespace Kubernetes\Client\Adapter\Http;

class AuthenticationMiddleware implements HttpClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param HttpClient $httpClient
     * @param string     $username
     * @param string     $password
     */
    public function __construct(HttpClient $httpClient, $username, $password)
    {
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        $basicAuthorizationString = base64_encode(sprintf('%s:%s', $this->username, $this->password));
        $options = array_merge_recursive([
            'headers' => [
                'Authorization' => 'Basic '.$basicAuthorizationString,
            ],
        ]);

        return $this->httpClient->request($method, $path, $body, $options);
    }
}
