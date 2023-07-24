<?php

namespace Msc\MscCryptoExchange\Requests;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Msc\MscCryptoExchange\Contracts\RequestFactory as RequestFactoryAlias;
use Psr\Http\Message\RequestInterface;

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
     * @param  int  $requestId
     * @return \GuzzleHttp\Psr7\Request
     */
    public function create(string $method, string $uri, int $requestId): RequestInterface
    {
        if (! $this->httpClient) {
            throw new \RuntimeException("Can't create request before configuring HTTP client");
        }

        return new Request($this->httpClient->withHeaders(['RequestId' => $requestId]), $method, $uri);
    }
}
