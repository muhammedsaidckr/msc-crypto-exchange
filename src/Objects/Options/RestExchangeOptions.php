<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Carbon\Carbon;
use Msc\MscCryptoExchange\Authentication\ApiCredentials;

class RestExchangeOptions extends ExchangeOptions
{
    /**
     * Whether or not to automatically sync the local time with the server time
     *
     * @var bool
     */
    public bool $autoTimestamp = false;

    /**
     * How often the timestamp adjustment between client and server is recalculated.
     * If you need a very small TimeSpan here you're probably better off syncing your server time more often
     *
     *
     */
    public $timestampRecalculationInterval;

    public function __construct(int $requestTimeoutInSeconds = 20)
    {
        parent::__construct($requestTimeoutInSeconds);
        $this->timestampRecalculationInterval = 10;
    }

    /**
     * Create a copy of this options
     *
     * @return static
     */
    public function copy()
    {
        $copy = new static();
        $copy->outputOriginalData = $this->outputOriginalData;
        $copy->autoTimestamp = $this->autoTimestamp;
        $copy->timestampRecalculationInterval = $this->timestampRecalculationInterval;
        $copy->apiCredentials = $this->apiCredentials?->copy();
        $copy->proxy = $this->proxy;
        $copy->requestTimeout = $this->requestTimeout;
        return $copy;
    }
}
