<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class CantConnectError extends Error
{
    public function __construct()
    {
        parent::__construct(null, "Can't connect to the server", null);
    }
}