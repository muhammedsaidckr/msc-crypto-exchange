<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class ArgumentError extends Error
{
    public function __construct(string $message)
    {
        parent::__construct(null, "Invalid parameter: ".$message, null);
    }
}