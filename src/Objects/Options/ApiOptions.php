<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;

class ApiOptions
{
    protected $outputOriginalData;
    protected $apiCredentials;

    public function getOutputOriginalData()
    {
        return $this->outputOriginalData;
    }

    public function setOutputOriginalData(bool $outputOriginalData)
    {
        $this->outputOriginalData = $outputOriginalData;
    }

    public function getApiCredentials()
    {
        return $this->apiCredentials;
    }

    public function setApiCredentials(?ApiCredentials $apiCredentials)
    {
        $this->apiCredentials = $apiCredentials;
    }
}
