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
    private $usernameOrToken;

    /**
     * @var string
     */
    private $password;

    /**
     * @param HttpClient $httpClient
     * @param string     $usernameOrToken
     * @param string     $password
     */
    public function __construct(HttpClient $httpClient, $usernameOrToken, $password = null)
    {
        $this->httpClient = $httpClient;
        $this->usernameOrToken = $usernameOrToken;
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        return $this->httpClient->request($method, $path, $body, $this->addAuthenticationHeader($options));
    }

    /**
     * {@inheritdoc}
     */
    public function asyncRequest($method, $path, $body = null, array $options = [])
    {
        return $this->httpClient->asyncRequest($method, $path, $body, $this->addAuthenticationHeader($options));
    }

    /**
     * @return string
     */
    private function getBasicAuthorizationString()
    {
        return 'Basic '.base64_encode(sprintf('%s:%s', $this->usernameOrToken, $this->password));
    }

    /**
     * @return string
     */
    private function getTokenAuthorizationString()
    {
        return 'Bearer '.$this->usernameOrToken;
    }

    /**
     * @return bool
     */
    private function isTokenAuthentication()
    {
        return null === $this->password;
    }

    private function addAuthenticationHeader(array $options): array
    {
        $authorizationHeader = $this->isTokenAuthentication() ? $this->getTokenAuthorizationString() : $this->getBasicAuthorizationString();

        return array_merge_recursive([
            'headers' => [
                'Authorization' => $authorizationHeader,
            ],
        ], $options);
    }
}
