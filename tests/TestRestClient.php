<?php

namespace Msc\MscCryptoExchange\Tests;


use Msc\MscCryptoExchange\Clients\BaseRestClient;
use Psr\Http\Message\RequestInterface;

class TestRestClient extends BaseRestClient
{
    public TestRestApi1Client $api1Client;

    public TestRestApi2Client $api2Client;

    public function __construct(callable $optionsFunc = null)
    {
        $options = new TestClientOptions();
        if ($optionsFunc !== null) {
            $optionsFunc($options);
        }

        $this->withOptions($optionsFunc, $options, null);
    }

    private function withOptions(callable|null $optionsFunc, TestClientOptions $options, $params)
    {
        $options = new TestClientOptions();
        $options = $options->copy();
        if ($optionsFunc !== null) {
            $optionsFunc($options);
        }

        $this->api1Client = new TestRestApi1Client($options);
        $this->api2Client = new TestRestApi2Client($options);
    }

    public function setResponse(string $responseData, RequestInterface $request)
    {
        $expectedBytes = json_decode($responseData);
        $responseStream = fopen('php://memory', 'r+');
        fwrite($responseStream, json_encode($expectedBytes), collect($expectedBytes)['intData']);
        fseek($responseStream, 0, SEEK_SET);
    }
}