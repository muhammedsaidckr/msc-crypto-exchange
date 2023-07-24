<?php

test('requesting data should result in data', function () {
    $client = new \Msc\MscCryptoExchange\Tests\TestRestClient();
    $expected = new \Msc\MscCryptoExchange\Tests\TestObject('some data', 10,
        new \Money\Money(1.23, new \Money\Currency('BTC')));

    $client->setResponse(json_encode($expected), new \GuzzleHttp\Psr7\Request('GET', 'https://localhost:123'));
    $result = $client->api1Client->request();

    expect($result->isSuccess())->toBeTrue();
    expect($expected === $result->getRequestBody())->toBeTrue();
});