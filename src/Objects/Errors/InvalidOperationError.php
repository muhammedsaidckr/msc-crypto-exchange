<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class InvalidOperationError extends Error
{
    public function __construct(string $message)
    {
        parent::__construct(null, $message, null);
    }
}