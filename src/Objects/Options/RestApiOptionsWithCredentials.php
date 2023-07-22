<?php

namespace Msc\MscCryptoExchange\Objects\Options;

use Msc\MscCryptoExchange\Authentication\ApiCredentials;

class RestApiOptionsWithCredentials extends RestApiOptions
{
    protected $apiCredentials;

    public function getApiCredentials()
    {
        return $this->apiCredentials;
    }

    public function setApiCredentials(?ApiCredentials $apiCredentials)
    {
        $this->apiCredentials = $apiCredentials;
    }
}
