<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;

class RestExchangeOptionsWithEnvironmentAndApiCredentials extends RestExchangeOptionsWithEnvironment
{
    /**
     * The api credentials used for signing requests to this API.
     *
     * @var ApiCredentials|null
     */
    public ?ApiCredentials $apiCredentials = null;

    /**
     * Get the api credentials used for signing requests to this API.
     *
     * @return ApiCredentials|null
     */
    public function getApiCredentials(): ?ApiCredentials
    {
        return $this->apiCredentials;
    }

    /**
     * Set the api credentials used for signing requests to this API.
     *
     * @param  ApiCredentials|null  $apiCredentials
     * @return void
     */
    public function setApiCredentials(?ApiCredentials $apiCredentials): void
    {
        $this->apiCredentials = $apiCredentials;
    }

    /**
     * Create a copy of this options
     *
     * @return static
     */
    public function copy()
    {
        $copy = parent::copy();
        $copy->apiCredentials = $this->apiCredentials?->copy();
        return $copy;
    }
}