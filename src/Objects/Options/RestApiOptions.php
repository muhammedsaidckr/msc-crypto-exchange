<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;

class RestApiOptions extends ApiOptions
{
    protected $rateLimiters = [];
    protected $rateLimitingBehaviour;
    protected $autoTimestamp;
    protected $timestampRecalculationInterval;

    public function getRateLimiters()
    {
        return $this->rateLimiters;
    }

    public function setRateLimiters(array $rateLimiters)
    {
        $this->rateLimiters = $rateLimiters;
    }

    public function getRateLimitingBehaviour()
    {
        return $this->rateLimitingBehaviour;
    }

    /**
     * @param  string|null  $rateLimitingBehaviour
     * @return void
     */
    public function setRateLimitingBehaviour(string|null $rateLimitingBehaviour)
    {
        $this->rateLimitingBehaviour = $rateLimitingBehaviour;
    }

    public function getAutoTimestamp()
    {
        return $this->autoTimestamp;
    }

    /**
     * @param bool|null $autoTimestamp
     * @return void
     */
    public function setAutoTimestamp($autoTimestamp)
    {
        $this->autoTimestamp = $autoTimestamp;
    }

    public function getTimestampRecalculationInterval()
    {
        return $this->timestampRecalculationInterval;
    }

    public function setTimestampRecalculationInterval(?string $timestampRecalculationInterval)
    {
        $this->timestampRecalculationInterval = $timestampRecalculationInterval;
    }

    public function copy()
    {
        $copy = new static();
        $copy->setApiCredentials($this->getApiCredentials() ? $this->getApiCredentials()->copy() : null);
        $copy->setOutputOriginalData($this->getOutputOriginalData());
        $copy->setAutoTimestamp($this->getAutoTimestamp());
        $copy->setRateLimiters($this->getRateLimiters());
        $copy->setRateLimitingBehaviour($this->getRateLimitingBehaviour());
        $copy->setTimestampRecalculationInterval($this->getTimestampRecalculationInterval());

        return $copy;
    }
}
