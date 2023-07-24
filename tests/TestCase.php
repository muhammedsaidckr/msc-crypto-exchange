<?php

namespace Msc\MscCryptoExchange\Tests;

use Msc\MscCryptoExchange\MscCryptoExchangeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            MscCryptoExchangeServiceProvider::class
        ];
    }
}

class TestObjectResult
{
    public TestObject2 $innerData;

    public function __construct()
    {
        $this->innerData = new TestObject2();
    }
}

class TestObject2
{
}

