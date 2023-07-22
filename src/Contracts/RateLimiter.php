<?php

namespace Msc\MscCryptoExchange\Contracts;

use Illuminate\Support\Facades\Log;
use Msc\MscCryptoExchange\Objects\CallResult;

interface RateLimiter
{
    public function limitRequestAsync(
        Log $log,
        string $endpoint,
        string $method,
        bool $signed,
        ?string $apiKey,
        string $limitBehaviour,
        int $requestWeight,
        \Closure $ct
    ): CallResult;
}
