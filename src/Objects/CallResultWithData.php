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

    /**
     * @param $data
     * @return \Msc\MscCryptoExchange\Objects\CallResult
     */
    public function as($data)
    {
        return new CallResultWithData($data, $this->originalData, $this->error);
    }

    public function asDataless()
    {
        return new CallResult();
    }

    public function asError(Error $error)
    {
        return new CallResultWithData(null, null, $error);
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        return $this->isSuccess();
    }

    public function __toString()
    {
        $result = parent::__toString();
        if ($this->isSuccess()) {
            return $result;
        } else {
            return $result." in ".$this->responseTime;
        }
    }
}
