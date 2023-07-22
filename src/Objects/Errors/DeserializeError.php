<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class DeserializeError extends Error
{
    public function __construct(string $message, $data)
    {
        parent::__construct(null, $message, $data);
    }
}