<?php

namespace Kubernetes\Client\Adapter\Http;

use function Kubernetes\Client\file_path_from_contents;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

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
     * @var string|array
     */
    private $credentials;

    /**
     * @param HttpClient $httpClient
     * @param string $authenticationType
     * @param string|array $credentials
     */
    public function __construct(HttpClient $httpClient, string $authenticationType, $credentials)
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
            if (is_array($this->credentials)) {
                $certificate = file_path_from_contents($this->credentials[0]);

                // Somehow, having the password of the certificate in an array (path + cert) do not work on Linux.
                // We therefore need to create a temporary, un-passwored, certificate
                try {
                    $certificate = file_path_from_contents(
                        (new Process(sprintf(
                            'openssl pkcs12 -in %s -nodes -password pass:%s',
                            $certificate,
                            $this->credentials[1]
                        )))->mustRun()->getOutput()
                    );
                } catch (RuntimeException $e) {
                    throw new \RuntimeException(sprintf(
                        'Could not read the client certificate: %s',
                        $e->getMessage()
                    ), $e->getCode(), $e);
                }
            } else {
                $certificate = file_path_from_contents($this->credentials);
            }

            $authorizationOptions = [
                'cert' => $certificate,
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
