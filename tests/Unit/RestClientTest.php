<?php

test('requesting data should result in data', function () {
    $client = \Msc\MscCryptoExchange\Tests\TestRestClient::create(function (
        \Msc\MscCryptoExchange\Tests\TestClientOptions $options
    ) {
        $options->api1Options->setApiCredentials(new \Msc\MscCryptoExchange\Authentication\ApiCredentials('test',
            'test'));
        $options->api2Options->setApiCredentials(new \Msc\MscCryptoExchange\Authentication\ApiCredentials('test',
            'test'));
    });
    $expected = new \Msc\MscCryptoExchange\Tests\TestObject('some data', 10,
        1.23);
    $client->setResponse(json_encode($expected), new \GuzzleHttp\Psr7\Request('GET', 'https://localhost:8033'));
    $result = $client->api1Client->request();

    expect($result->isSuccess())->toBeTrue();
    expect($expected === $result->getRequestBody())->toBeTrue();
});