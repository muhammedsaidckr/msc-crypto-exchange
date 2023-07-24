<?php

namespace Msc\MscCryptoExchange\Objects\Options;

class TradeEnvironment
{
    /**
     * URL for connecting to the test trading environment
     *
     * @var string
     */
    public string $environmentName;

    /**
     * Create a new TradeEnvironment instance
     *
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->environmentName = $name;
    }
}
