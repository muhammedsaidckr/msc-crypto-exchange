<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class NoApiCredentialsError extends Error
{
    public function __construct()
    {
        parent::__construct(null, "No credentials provided for private endpoint", null);
    }
}