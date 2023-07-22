<?php

namespace Msc\MscCryptoExchange\Contracts;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;

interface BaseApiClient
{
    /**
     * Get the base address for this API client.
     *
     * @return string
     */
    public function getBaseAddress(): string;

    /**
     * Set the API credentials for this API client.
     *
     * @param ApiCredentials $credentials
     * @return void
     */
    public function setApiCredentials(ApiCredentials $credentials): void;
}
