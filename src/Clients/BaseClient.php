<?php

namespace Msc\MscCryptoExchange\Clients;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Exception\InvalidOptionException;

class BaseClient
{

    public $name;
    public $apiClients = array();
    public $clientOptions;
    public $logger;

    public function __construct($logger, $name)
    {
        $this->name = $name;
        $this->apiClients = array();
        $this->clientOptions = null;
        if (is_null($logger)) {
            $this->logger = new Logger('msc-crypto-exchange');
        } else {
            $this->logger = $logger;
        }
    }

    public function initialize($options)
    {
        $this->clientOptions = $options;
        $this->logger->trace("Client configuration: ".$options.", msc-crypto-exchange: ".", ".$this->name.": ".", PHP: ".phpversion());
    }

    public function setApiCredentials($credentials)
    {
        foreach ($this->apiClients as $apiClient) {
            $apiClient->setApiCredentials($credentials);
        }
    }

    public function addApiClient($apiClient)
    {
        if ($this->clientOptions === null) {
            throw new InvalidOptionException($this->id." client should have called initialize before adding API clients");
        }
        $this->logger->trace("  ".get_class($apiClient).", base address: ".$apiClient->baseAddress);
        $this->apiClients[] = $apiClient;
        return $apiClient;
    }

    public function __destruct()
    {
        foreach ($this->apiClients as $client) {
            $client->dispose();
        }
    }
}


