<?php

namespace Kubernetes\Client\Adapter\Http;

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
                'cert' => $this->createCertificateFile($this->credentials),
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

    private function createCertificateFile(string $certificateContents)
    {
        $file = tempnam(sys_get_temp_dir(), 'certificate');
        file_put_contents($file, $certificateContents);

        register_shutdown_function(function() use($file) {
            unlink($file);
        });

        return $file;
    }
}
