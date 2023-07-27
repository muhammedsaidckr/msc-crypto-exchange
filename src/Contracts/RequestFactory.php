<?php

namespace Msc\MscCryptoExchange\Contracts;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\UuidInterface;

interface RequestFactory
{
    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  \Ramsey\Uuid\UuidInterface  $requestId
     * @return \Psr\Http\Message\RequestInterface
     */
    public function create(string $method, string $uri, UuidInterface $requestId): RequestInterface;

    /**
     * @param  int  $requestTimeout
     * @param  \GuzzleHttp\Client|null  $httpClient
     * @return void
     */
    public function configure(int $requestTimeout, ?Client $httpClient = null): void;
}
