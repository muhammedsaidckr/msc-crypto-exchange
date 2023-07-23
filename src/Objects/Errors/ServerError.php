<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class ServerError extends Error
{
    public function __construct(string $message, $data = null)
    {
        parent::__construct(500, $message, $data);
    }
}