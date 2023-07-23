<?php

use Symfony\Component\HttpFoundation\Response;

test('test basic error call result', function () {
    $result = new \Msc\MscCryptoExchange\Objects\CallResult(new \Msc\MscCryptoExchange\Objects\Errors\ServerError(
        'test error'));

    expect($result->getError()->getMessage())->toBe('test error');
});

test('test basic success call result', function () {
    $object = new stdClass();
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData();

    expect($result->getError())->toBeNull();
    expect($result())->toBeTrue();
    expect($result->isSuccess())->toBeTrue();
});

test('test call result error', function () {
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData;
    $result = $result->asError(new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error'));

    expect($result->getError()->getMessage())->toBe('test error');
    expect($result->getData())->toBeNull();
    expect($result())->toBeFalse();
    expect($result->isSuccess())->toBeFalse();
});

test('test call result success', function () {
    $object = new \stdClass();
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData($object);

    expect($result->getError())->toBeNull();
    expect(! empty($result->getData()))->toBeTrue();
    expect($result())->toBeTrue();
    expect($result->isSuccess())->toBeTrue();
});

test('test call result success as', function () {
    $object = new \Msc\MscCryptoExchange\Tests\TestObjectResult();
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData($object);
    $asResult = $result->as($result->getData()->innerData);

    expect($asResult->getError())->toBeNull();
    expect(! empty($asResult->getData()))->toBeTrue();
    expect($asResult->getData() === \Msc\MscCryptoExchange\Tests\TestObject2::class);
    expect($asResult())->toBeTrue();
    expect($asResult->isSuccess())->toBeTrue();
});

test('test call result error as', function () {
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData(null,
        null, new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error'));
    $asResult = $result->as($result->getData());

    expect(is_null($asResult->getError()))->toBeFalse();
    expect($asResult->getError()->getMessage())->toBe('test error');
    expect($asResult->getData())->toBeNull();
    expect($asResult())->toBeFalse();
    expect($asResult->isSuccess())->toBeFalse();
});

test('test call result error as error', function () {
    $result = new \Msc\MscCryptoExchange\Objects\CallResultWithData(error: new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error'));
    $asResult = $result->asError(new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error 2'));

    expect(is_null($asResult->getError()))->toBeFalse();
    expect($asResult->getError()->getMessage())->toBe('test error 2');
    expect($asResult->getData())->toBeNull();
    expect($asResult())->toBeFalse();
    expect($asResult->isSuccess())->toBeFalse();
});

test('test web call result error as error', function () {
    $result = new \Msc\MscCryptoExchange\Objects\WebCallResultWithData(error: new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error'));
    $asResult = $result->asError(new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error 2'));

    expect(is_null($asResult->getError()))->toBeFalse();
    expect($asResult->getError()->getMessage())->toBe('test error 2');
    expect($asResult->getData())->toBeNull();
    expect($asResult())->toBeFalse();
    expect($asResult->isSuccess())->toBeFalse();
});

test('test web call result success as error', function () {
    $result = new \Msc\MscCryptoExchange\Objects\WebCallResultWithData(
        Response::HTTP_OK,
        [],
        \Carbon\Carbon::now()->diffInRealMilliseconds(\Carbon\Carbon::now()->addSecond(1)),
        "{}",
        null,
        'https://test.com/api',
        null,
        "GET",
        [],
        new \Msc\MscCryptoExchange\Tests\TestObjectResult(),
        null
    );
    $asResult = $result->asError(new \Msc\MscCryptoExchange\Objects\Errors\ServerError('test error 2'));

    expect(is_null($asResult->getError()))->toBeFalse();
    expect($asResult->getError()->getMessage())->toBe('test error 2');
    expect($asResult->getResponseStatusCode())->toBe(Response::HTTP_OK);
    expect($asResult->getResponseTime())->toBe(\Carbon\Carbon::now()->diffInRealMilliseconds(\Carbon\Carbon::now()->addSecond(1)));
    expect($asResult->getRequestUrl())->toBe('https://test.com/api');
    expect($asResult->getRequestMethod())->toBe('GET');
    expect($asResult->getData())->toBeNull();
    expect($asResult())->toBeFalse();
    expect($asResult->isSuccess())->toBeFalse();
});

test('test web call result success as success', function () {
    $result = new \Msc\MscCryptoExchange\Objects\WebCallResultWithData(
        Response::HTTP_OK,
        [],
        \Carbon\Carbon::now()->diffInRealMilliseconds(\Carbon\Carbon::now()->addSecond(1)),
        "{}",
        null,
        'https://test.com/api',
        null,
        "GET",
        [],
        new \Msc\MscCryptoExchange\Tests\TestObjectResult(),
        null
    );
    $asResult = $result->as($result->getData()->innerData);

    expect($asResult->getError())->toBeNull();
    expect($asResult->getResponseStatusCode())->toBe(Response::HTTP_OK);
    expect($asResult->getResponseTime())->toBe(\Carbon\Carbon::now()->diffInRealMilliseconds(\Carbon\Carbon::now()->addSecond(1)));
    expect($asResult->getRequestUrl())->toBe('https://test.com/api');
    expect($asResult->getRequestMethod())->toBe('GET');
    expect(empty($asResult->getData()))->toBeFalse();
    expect($asResult())->toBeTrue();
    expect($asResult->isSuccess())->toBeTrue();
});
