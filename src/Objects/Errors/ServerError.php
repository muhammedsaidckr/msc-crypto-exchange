<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class ServerError extends Error
{
    public function __construct(int $code, string $message, $data = null)
    {
        parent::__construct($code, $message, $data);
    }
}