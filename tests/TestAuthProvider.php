<?php

namespace Msc\MscCryptoExchange\Tests;

use GuzzleHttp\Psr7\Uri;
use Msc\MscCryptoExchange\Authentication\ApiCredentials;
use Msc\MscCryptoExchange\Authentication\AuthenticationProvider;
use Msc\MscCryptoExchange\Clients\RestApiClient;

class TestAuthProvider extends AuthenticationProvider
{
    public string $key;
    public string $secret;

    public function __construct(ApiCredentials $credentials)
    {
        parent::__construct($credentials);
        $this->key = $credentials->getKey();
        $this->secret = $credentials->getSecret();
    }

    /**
     * @param  \Msc\MscCryptoExchange\Clients\RestApiClient  $apiClient
     * @param  \GuzzleHttp\Psr7\Uri  $uri
     * @param  string  $method
     * @param  array  $provideParameters
     * @param  bool  $auth
     * @param  array  $uriParameters
     * @param  array  $bodyParameters
     * @param  array  $headers
     * @return array
     */
    public function authenticateRequest(
        RestApiClient $apiClient,
        Uri $uri,
        string $method,
        array $provideParameters,
        bool $auth,
        array $uriParameters,
        array $bodyParameters,
        array $headers,
    ) {
        $bodyParameters = $this->sortQuery($bodyParameters);
        $uriParameters = $this->sortQuery($uriParameters);
        $headers = array();

        return array(
            'bodyParameters' => $bodyParameters,
            'uriParameters' => $uriParameters,
            'headers' => $headers,
        );
    }

    public function sign(string $method, string $uri, array $params)
    {
        return parent::sign($method, $uri, $params);
    }
}