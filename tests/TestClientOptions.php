<?php

namespace Msc\MscCryptoExchange\Tests;

use Msc\MscCryptoExchange\Objects\Options\RestApiOptions;
use Msc\MscCryptoExchange\Objects\Options\RestExchangeOptionsWithEnvironmentAndApiCredentials;

class TestClientOptions extends RestExchangeOptionsWithEnvironmentAndApiCredentials
{
    protected $receiveWindow;
    public RestApiOptions $api1Options;

    public RestApiOptions $api2Options;

    public function __construct()
    {
        parent::__construct();
        $this->environment = new TestEnvironment('test', 'https://test.com');
        $this->receiveWindow = 5000;
        $this->api1Options = new RestApiOptions();
        $this->api2Options = new RestApiOptions();
    }


    /**
     * @return \Msc\MscCryptoExchange\Tests\TestClientOptions
     */
    public function copy()
    {
        $options = parent::copy();
        $options->api1Options = $this->api1Options->copy();
        $options->api2Options = $this->api2Options->copy();
        return $options;
    }
}