<?php

namespace Msc\MscCryptoExchange\Tests;

use Illuminate\Log\Logger;
use Msc\MscCryptoExchange\Authentication\ApiCredentials;
use Msc\MscCryptoExchange\Clients\RestApiClient;
use Msc\MscCryptoExchange\Contracts\DateTimeContract;
use Msc\MscCryptoExchange\Contracts\RequestFactory;
use Msc\MscCryptoExchange\Objects\TimeSyncInfo;
use Nette\NotImplementedException;
use Psr\Http\Message\RequestFactoryInterface;

class TestRestApi1Client extends RestApiClient
{
    public function __construct(
        TestClientOptions $options,
    ) {
        parent::__construct('https://localhost:123', app(Logger::class), null, $options, $options->api1Options);
//        $requestFactoryMock = \Mockery::mock(RequestFactoryInterface::class);
//
//        $requestFactoryMock->shouldReceive('Create')
//            ->andReturn('test');
//
//        $requestFactoryMock->shouldReceive('Configure')
//            ->with('')
//            ->once();
//
//        \Mockery::close();
    }

    /**
     * @return \Msc\MscCryptoExchange\Objects\WebCallResult
     */
    public function request()
    {
        return $this->sendRequestAsync(
            new \GuzzleHttp\Psr7\Uri('https://localhost:123'),
            'GET',
            function () {
            },
            null,
            false,
            1,
            null,
            false
        );
    }

    public function requestWithParams(string $method, array $parameters, array $headers)
    {
        return $this->sendRequestAsync(
            new \GuzzleHttp\Psr7\Uri('https://localhost:123'),
            $method,
            function () {
            },
            $parameters,
            false,
            1,
            $headers,
            false
        );
    }

    public function getTimeOffset(): ?DateTimeContract
    {
        throw new NotImplementedException();
    }

    /**
     * @param  \Msc\MscCryptoExchange\Authentication\ApiCredentials  $apiCredentials
     * @return \Msc\MscCryptoExchange\Tests\TestAuthProvider
     */
    protected function createAuthenticationProvider(ApiCredentials $apiCredentials)
    {
        return new TestAuthProvider($apiCredentials);
    }

    public function getTimeSyncInfo(): ?TimeSyncInfo
    {
        throw new NotImplementedException();
    }

    public function getBaseAddress(): string
    {
        // TODO: Implement getBaseAddress() method.
    }

    public function setApiCredentials(ApiCredentials $credentials): void
    {
        // TODO: Implement setApiCredentials() method.
    }

    public function getRequestFactory(): RequestFactory
    {
        // TODO: Implement getRequestFactory() method.
    }

    public function setRequestFactory(RequestFactory $requestFactory): void
    {
        // TODO: Implement setRequestFactory() method.
    }

    public function getTotalRequestsMade(): int
    {
        // TODO: Implement getTotalRequestsMade() method.
    }

    public function setTotalRequestsMade(int $totalRequestsMade): void
    {
        // TODO: Implement setTotalRequestsMade() method.
    }
}