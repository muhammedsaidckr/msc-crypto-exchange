<?php

namespace Msc\MscCryptoExchange\Objects;


class CallResultWithData extends CallResult
{
    protected $data;
    protected $originalData;

    public function __construct($data = null, $originalData = null, $error = null)
    {
        parent::__construct($error);
        $this->data = $data;
        $this->originalData = $originalData;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOriginalData()
    {
        return $this->originalData;
    }

    public function asDataless()
    {
        return new CallResult();
    }

    public function asDatalessError(Error $error)
    {
        return new CallResult($error);
    }

    public function asError(Error $error)
    {
        return new CallResultWithData(null, null, $error);
    }

    public function __toString()
    {
        $result = parent::__toString();
        if ($this->isSuccess()) {
            return $result;
        } else {
            return $result . " in " . $this->responseTime;
        }
    }
}
