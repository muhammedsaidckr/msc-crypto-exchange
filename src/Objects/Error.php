<?php

namespace Msc\MscCryptoExchange\Objects;

abstract class Error
{
    protected $code;
    protected $message;
    protected $data;

    public function __construct(int $code = null, string $message, $data = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        return "{$this->code}: {$this->message} {$this->data}";
    }
}
