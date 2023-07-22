<?php

namespace Msc\MscCryptoExchange\Contracts;

use Msc\MscCryptoExchange\Objects\Options\ExchangeOptions;

interface RestClient
{
    /**
     * The options provided for this client
     *
     * @return ExchangeOptions
     */
    public function getClientOptions(): ExchangeOptions;

    /**
     * The total amount of requests made with this client
     *
     * @return int
     */
    public function getTotalRequestsMade(): int;
}