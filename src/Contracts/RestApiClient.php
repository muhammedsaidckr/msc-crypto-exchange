<?php

namespace Msc\MscCryptoExchange\Contracts;

use Msc\MscCryptoExchange\Objects\TimeSyncInfo;

interface RestApiClient extends BaseApiClient
{
    /**
     * Get the factory for creating requests. Used for unit testing.
     *
     * @return \Msc\MscCryptoExchange\Contracts\RequestFactory
     */
    public function getRequestFactory(): RequestFactory;

    /**
     * Set the factory for creating requests. Used for unit testing.
     *
     * @param \Msc\MscCryptoExchange\Contracts\RequestFactory $requestFactory
     * @return void
     */
    public function setRequestFactory(RequestFactory $requestFactory): void;

    /**
     * Get the total amount of requests made with this API client.
     *
     * @return int
     */
    public function getTotalRequestsMade(): int;

    /**
     * Set the total amount of requests made with this API client.
     *
     * @param int $totalRequestsMade
     * @return void
     */
    public function setTotalRequestsMade(int $totalRequestsMade): void;

    /**
     * Get the time offset for an API client. Return null if time syncing shouldn't/can't be done.
     *
     * @return \Msc\MscCryptoExchange\Contracts\DateTimeContract|null
     */
    public function getTimeOffset(): ?DateTimeContract;

    /**
     * Get the time sync info for an API client. Return null if time syncing shouldn't/can't be done.
     *
     * @return TimeSyncInfo|null
     */
    public function getTimeSyncInfo(): ?TimeSyncInfo;
}
