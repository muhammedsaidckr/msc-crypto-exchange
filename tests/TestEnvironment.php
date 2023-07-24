<?php

namespace Msc\MscCryptoExchange\Tests;

use Msc\MscCryptoExchange\Objects\Options\TradeEnvironment;

class TestEnvironment extends TradeEnvironment
{
    public string $testAddress;

    public function __construct(string $name, string $url)
    {
        parent::__construct($name);
        $this->testAddress = $url;
    }
}