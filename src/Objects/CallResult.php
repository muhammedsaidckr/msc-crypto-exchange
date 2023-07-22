<?php

namespace Msc\MscCryptoExchange\Objects;

class CallResult
{
    protected $error;

    public function __construct(Error $error = null)
    {
        $this->error = $error;
    }

    /**
     * @return \Msc\MscCryptoExchange\Objects\Error|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->error === null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isSuccess() ? "Success" : "Error: ".$this->error;
    }
}
