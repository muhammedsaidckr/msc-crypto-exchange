<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class RateLimitError extends Error
{
    public function __construct(string $message)
    {
        parent::__construct(null, "Rate limit exceeded: ".$message, null);
    }
}