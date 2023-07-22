<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class UnknownError extends Error
{
    public function __construct(string $message, $data = null)
    {
        parent::__construct(null, $message, $data);
    }
}