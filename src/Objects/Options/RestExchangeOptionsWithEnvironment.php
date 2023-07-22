<?php

namespace Msc\MscCryptoExchange\Objects\Options;

class RestExchangeOptionsWithEnvironment extends RestExchangeOptions
{
    /**
     * Trade environment. Contains info about URL's to use to connect to the API.
     * To swap environment, select another environment for the exchange's environment list
     * or create a custom environment using either `[Exchange]Environment::createCustom()` or `[Exchange]Environment::[Environment]`,
     * for example `KucoinEnvironment::testNet()` or `BinanceEnvironment::live()`
     *
     * @var TradeEnvironment
     */
    public TradeEnvironment $environment;

    /**
     * Create a copy of this options
     *
     * @return static
     */
    public function copy()
    {
        $copy = parent::copy();
        $copy->environment = $this->environment;
        return $copy;
    }
}