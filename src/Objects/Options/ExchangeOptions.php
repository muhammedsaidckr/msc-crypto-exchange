<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;
use Msc\MscCryptoExchange\Objects\ApiProxy;
use Illuminate\Support\Carbon;

class ExchangeOptions
{
    /**
     * Proxy settings
     *
     * @var ApiProxy|null
     */
    public ?ApiProxy $proxy = null;

    /**
     * If true, the CallResult and DataEvent objects will also include the originally received json data in the OriginalData property
     *
     * @var bool
     */
    public bool $outputOriginalData = false;

    /**
     * The max time a request is allowed to take
     *
     *
     */
    public $requestTimeout;

    /**
     * The api credentials used for signing requests to this API.
     *
     * @var ApiCredentials|null
     */
    public ?ApiCredentials $apiCredentials;

    /**
     * ExchangeOptions constructor.
     *
     * @param  int  $requestTimeoutInSeconds
     */
    public function __construct(int $requestTimeoutInSeconds = 20)
    {
        $this->requestTimeout = 20;
    }

    /**
     * Get the proxy settings
     *
     * @return ApiProxy|null
     */
    public function getProxy(): ?ApiProxy
    {
        return $this->proxy;
    }

    /**
     * Set the proxy settings
     *
     * @param  ApiProxy|null  $proxy
     * @return void
     */
    public function setProxy(?ApiProxy $proxy): void
    {
        $this->proxy = $proxy;
    }

    /**
     * Get the max time a request is allowed to take
     *
     * @return Carbon
     */
    public function getRequestTimeout()
    {
        return $this->requestTimeout;
    }

    /**
     * Set the max time a request is allowed to take
     *
     * @param  int  $requestTimeoutInSeconds
     * @return void
     */
    public function setRequestTimeout(int $requestTimeoutInSeconds): void
    {
        $this->requestTimeout = Carbon::createFromTimestamp($requestTimeoutInSeconds);
    }

    /**
     * Get the api credentials used for signing requests to this API
     *
     * @return ApiCredentials|null
     */
    public function getApiCredentials(): ?ApiCredentials
    {
        return $this->apiCredentials;
    }

    /**
     * Set the api credentials used for signing requests to this API
     *
     * @param  ApiCredentials|null  $apiCredentials
     * @return void
     */
    public function setApiCredentials(?ApiCredentials $apiCredentials): void
    {
        $this->apiCredentials = $apiCredentials;
    }

    /**
     * Convert the object to a string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        $proxyStatus = $this->proxy ? 'set' : '-';
        $credentialsStatus = $this->apiCredentials ? 'set' : '-';
        return "RequestTimeout: {$this->requestTimeout->toTimeString()}, Proxy: {$proxyStatus}, ApiCredentials: {$credentialsStatus}";
    }
}
