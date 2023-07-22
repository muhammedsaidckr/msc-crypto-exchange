<?php

namespace Msc\MscCryptoExchange\Objects\Options;

class TradeEnvironment
{
    /**
     * URL for connecting to the live trading environment
     *
     * @var string
     */
    public string $liveUrl;

    /**
     * URL for connecting to the test trading environment
     *
     * @var string
     */
    public string $testUrl;

    /**
     * Create a new TradeEnvironment instance
     *
     * @param  string  $liveUrl
     * @param  string  $testUrl
     */
    public function __construct(string $liveUrl, string $testUrl)
    {
        $this->liveUrl = $liveUrl;
        $this->testUrl = $testUrl;
    }

    /**
     * Create a custom trade environment
     *
     * @param  string  $liveUrl
     * @param  string  $testUrl
     * @return TradeEnvironment
     */
    public static function createCustom(string $liveUrl, string $testUrl)
    {
        return new self($liveUrl, $testUrl);
    }

    /**
     * Get the live trading environment
     *
     * @return TradeEnvironment
     */
    public static function live()
    {
        return new self('https://api.example.com/live', 'https://test-api.example.com');
    }

    /**
     * Get the test trading environment
     *
     * @return TradeEnvironment
     */
    public static function testNet()
    {
        return new self('https://api.example.com/test', 'https://test-api.example.com');
    }
}
