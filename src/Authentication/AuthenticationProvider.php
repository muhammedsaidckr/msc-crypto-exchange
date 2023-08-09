<?php

namespace Msc\MscCryptoExchange\Authentication;

use GuzzleHttp\Psr7\Uri;
use Msc\MscCryptoExchange\Clients\RestApiClient;
use Msc\MscCryptoExchange\Objects\Enums\HttpMethodParameterPosition;

abstract class AuthenticationProvider
{
    protected ApiCredentials $credentials;
    protected $sBytes;

    public function __construct(ApiCredentials $apiCredentials)
    {
        if ($apiCredentials->getSecret() == null) {
            throw new \InvalidArgumentException("ApiCredentials must have a secret");
        }

        $this->credentials = $apiCredentials;
        $this->sBytes = pack('A*', $apiCredentials->getSecret());
    }

    public abstract function authenticateRequest(
        RestApiClient $apiClient,
        Uri $uri,
        string $method,
        array $provideParameters,
        bool $auth,
        array $arraySerialization,
        HttpMethodParameterPosition $parameterPosition,
        array $uriParameters,
        array $bodyParameters,
        array $headers,
    );

    /**
     * @return \Msc\MscCryptoExchange\Authentication\ApiCredentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param  string  $data
     * @return string
     */
    public function getSignature(string $data)
    {
        return hash_hmac('sha256', $data, $this->sBytes);
    }

    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $params
     * @return string
     */
    public function getSignatureBase(string $method, string $uri, array $params)
    {
        $uri = parse_url($uri);
        $path = $uri['path'];
        $query = $uri['query'] ?? '';

        $query = $this->normalizeQuery($query, $params);
        $query = $this->sortQuery($query);
        $query = $this->encodeQuery($query);

        return $method.'&'.urlencode($path).'&'.urlencode($query);
    }

    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $params
     * @return string
     */
    public function sign(string $method, string $uri, array $params)
    {
        $base = $this->getSignatureBase($method, $uri, $params);
        return $this->getSignature($base);
    }

    /**
     * @param  string  $query
     * @param  array  $params
     * @return array
     */
    public function normalizeQuery(string $query, array $params)
    {
        $query = $this->parseQuery($query);
        $query = $this->addParams($query, $params);
        return $query;
    }

    /**
     * @param  string  $query
     * @return array
     */
    public function parseQuery(string $query)
    {
        $parsed = [];
        parse_str($query, $parsed);
        return $parsed;
    }

    /**
     * @param  array  $query
     * @param  array  $params
     * @return array
     */
    public function addParams(array $query, array $params)
    {
        foreach ($params as $key => $value) {
            $query[$key] = $value;
        }
        return $query;
    }

    /**
     * @param  array  $query
     * @return array
     */
    public function sortQuery(array $query)
    {
        ksort($query);
        return $query;
    }

    /**
     * @param  array  $query
     * @return string
     */
    public function encodeQuery(array $query)
    {
        $encoded = [];
        foreach ($query as $key => $value) {
            $encoded[] = urlencode($key).'='.urlencode($value);
        }
        return implode('&', $encoded);
    }
}