<?php

namespace Msc\MscCryptoExchange\Authentication;

use Msc\MscCryptoExchange\Helpers\JsonHelper;

class ApiCredentials
{
    /**
     * @var string|null The api key to authenticate requests
     */
    protected $key;

    /**
     * @var string|null The api secret to authenticate requests
     */
    protected $secret;

    /**
     * @var ApiCredentialsType Type of the credentials
     */
    protected $credentialType;

    /**
     * ApiCredentials constructor.
     *
     * @param  string  $key  The api key used for identification
     * @param  string  $secret  The api secret used for signing
     * @param  ApiCredentialsType  $credentialsType  The type of credentials
     */
    public function __construct(
        string $key,
        string $secret,
        ApiCredentialsType $credentialsType = ApiCredentialsType::HMAC
    ) {
        if (empty($key) || empty($secret)) {
            throw new \InvalidArgumentException("Key and secret can't be empty");
        }

        $this->credentialType = $credentialsType;
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * Get the api key
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Get the api secret
     *
     * @return string|null
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * Get the type of credentials
     *
     * @return ApiCredentialsType
     */
    public function getCredentialType(): ApiCredentialsType
    {
        return $this->credentialType;
    }

    /**
     * Copy the credentials
     *
     * @return ApiCredentials
     */
    public function copy(): ApiCredentials
    {
        return new ApiCredentials($this->key, $this->secret, $this->credentialType);
    }

    /**
     * Create Api credentials providing a JSON string data. The JSON data should include two values: apiKey and apiSecret
     *
     * @param  string  $jsonData  The JSON data as string
     * @param  string|null  $identifierKey  A key to identify the credentials for the API. For example, when set to `binanceKey` the JSON data should contain a value for the property `binanceKey`. Defaults to 'apiKey'.
     * @param  string|null  $identifierSecret  A key to identify the credentials for the API. For example, when set to `binanceSecret` the JSON data should contain a value for the property `binanceSecret`. Defaults to 'apiSecret'.
     * @return ApiCredentials
     * @throws \InvalidArgumentException
     */
    public static function fromJson(
        string $jsonData,
        string $identifierKey = null,
        string $identifierSecret = null
    ): ApiCredentials {
        $data = JsonHelper::decode($jsonData, true);

        $key = self::tryGetValue($data, $identifierKey ?? 'apiKey');
        $secret = self::tryGetValue($data, $identifierSecret ?? 'apiSecret');

        if (empty($key) || empty($secret)) {
            throw new \InvalidArgumentException("apiKey or apiSecret value not found in JSON credential data");
        }

        return new ApiCredentials($key, $secret);
    }

    /**
     * Try get the value of a key from an array
     *
     * @param  array  $data
     * @param  string  $key
     * @return string|null
     */
    protected static function tryGetValue(array $data, string $key): ?string
    {
        return isset($data[$key]) ? (string)$data[$key] : null;
    }
}
