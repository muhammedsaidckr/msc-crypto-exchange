<?php

namespace Msc\MscCryptoExchange\Requests;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Msc\MscCryptoExchange\Contracts\RequestFactory as RequestFactoryAlias;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class RequestFactory implements RequestFactoryAlias
{
    private $httpClient;

    public function configure(\DateInterval|Carbon|int $requestTimeout, $httpClient = null): void
    {
        $this->httpClient = $httpClient ?? Http::timeout($requestTimeout);
    }


    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  \Ramsey\Uuid\UuidInterface  $requestId
     * @return \GuzzleHttp\Psr7\Request
     */
    public function create(string $method, string $uri, UuidInterface $requestId): RequestInterface
    {
        if (! $this->httpClient) {
            throw new \RuntimeException("Can't create request before configuring HTTP client");
        }

        return new Request($method, $uri, [
            'X-Request-Id' => $requestId->toString(),
        ]);
    }
}
