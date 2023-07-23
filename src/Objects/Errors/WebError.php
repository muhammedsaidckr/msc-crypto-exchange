<?php

namespace Msc\MscCryptoExchange\Objects\Errors;

use Msc\MscCryptoExchange\Objects\Error;

class WebError extends Error
{
    public function __construct(int|null $code, string $message, $data = null)
    {
        parent::__construct($code, $message, $data);
    }
}