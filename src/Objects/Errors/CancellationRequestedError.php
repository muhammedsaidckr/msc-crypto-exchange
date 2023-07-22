<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class CancellationRequestedError extends Error
{
    public function __construct()
    {
        parent::__construct(null, "Cancellation requested", null);
    }
}