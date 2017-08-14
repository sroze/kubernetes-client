<?php

namespace Kubernetes\Client\Adapter\Http;

require_once 'functions.php';

class AuthenticationMiddleware implements HttpClient
{
    const USERNAME_PASSWORD = 'username:password';
    const TOKEN = 'token';
    const CERTIFICATE = 'certificate';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $authenticationType;

    /**
     * @var string
     */
    private $credentials;

    /**
     * @param HttpClient $httpClient
     * @param string $authenticationType
     * @param string $credentials
     */
    public function __construct(HttpClient $httpClient, string $authenticationType, string $credentials)
    {
        $this->httpClient = $httpClient;
        $this->authenticationType = $authenticationType;
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        return $this->httpClient->request($method, $path, $body, $this->addAuthenticationOptions($options));
    }

    /**
     * {@inheritdoc}
     */
    public function asyncRequest($method, $path, $body = null, array $options = [])
    {
        return $this->httpClient->asyncRequest($method, $path, $body, $this->addAuthenticationOptions($options));
    }

    /**
     * @return string
     */
    private function getBasicAuthorizationString()
    {
        return 'Basic '.base64_encode($this->credentials);
    }

    /**
     * @return string
     */
    private function getTokenAuthorizationString()
    {
        return 'Bearer '.$this->credentials;
    }

    /**
     * @return bool
     */
    private function isTokenAuthentication()
    {
        return self::TOKEN == $this->authenticationType;
    }

    private function addAuthenticationOptions(array $options): array
    {
        if (self::CERTIFICATE == $this->authenticationType) {
            $authorizationOptions = [
                'cert' => certificate_file_path_from_contents($this->credentials),
            ];
        } else {
            $authorizationOptions = [
                'headers' => [
                    'Authorization' => $this->isTokenAuthentication() ? $this->getTokenAuthorizationString() : $this->getBasicAuthorizationString(),
                ],
            ];
        }

        return array_merge_recursive($authorizationOptions, $options);
    }
}
