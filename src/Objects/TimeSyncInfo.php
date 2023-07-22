<?php

namespace Msc\MscCryptoExchange\Objects;

class TimeSyncInfo
{
    /**
     * The server's timestamp when the request was sent
     *
     * @var int
     */
    public $requestSentTimestamp;

    /**
     * The server's timestamp when the response was received
     *
     * @var int
     */
    public $responseReceivedTimestamp;

    /**
     * The client's timestamp when the response was received
     *
     * @var int
     */
    public $clientReceivedTimestamp;

    /**
     * The time offset between the client and the server
     *
     * @var int
     */
    public $timeOffset;

    /**
     * TimeSyncInfo constructor.
     *
     * @param  int  $requestSentTimestamp  The server's timestamp when the request was sent
     * @param  int  $responseReceivedTimestamp  The server's timestamp when the response was received
     * @param  int  $clientReceivedTimestamp  The client's timestamp when the response was received
     */
    public function __construct(int $requestSentTimestamp, int $responseReceivedTimestamp, int $clientReceivedTimestamp)
    {
        $this->requestSentTimestamp = $requestSentTimestamp;
        $this->responseReceivedTimestamp = $responseReceivedTimestamp;
        $this->clientReceivedTimestamp = $clientReceivedTimestamp;

        // Calculate the time offset between the client and the server
        $this->timeOffset = $this->calculateTimeOffset();
    }

    /**
     * Calculate the time offset between the client and the server
     *
     * @return int The time offset in seconds (server time - client time)
     */
    private function calculateTimeOffset()
    {
        // Calculate the difference between the server and client timestamps
        return $this->responseReceivedTimestamp - $this->clientReceivedTimestamp;
    }
}
