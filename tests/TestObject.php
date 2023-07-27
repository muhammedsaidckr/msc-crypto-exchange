<?php

namespace Msc\MscCryptoExchange\Tests;

use Money\Money;

class TestObject
{
    public function __construct(public string $stringData, public int $intData, public $decimalData)
    {
    }
}