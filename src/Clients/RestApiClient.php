<?php

namespace Msc\MscCryptoExchange\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Log\Logger;
use Msc\MscCryptoExchange\Contracts\DateTimeContract;
use Msc\MscCryptoExchange\Contracts\Request;
use Msc\MscCryptoExchange\Contracts\RestApiClient as RestApiClientAlias;
use Msc\MscCryptoExchange\Objects\CallResult;
use Msc\MscCryptoExchange\Objects\Options\RestApiOptions;
use Msc\MscCryptoExchange\Objects\Options\RestExchangeOptions;
use Msc\MscCryptoExchange\Objects\TimeSyncInfo;
use Msc\MscCryptoExchange\Objects\WebCallResult;
use Msc\MscCryptoExchange\Requests\RequestFactory;
use Psr\Http\Message\UriInterface;

abstract class RestApiClient extends BaseApiClient implements RestApiClientAlias
{
    protected RequestFactory $requestFactory;
    protected ?int $totalRequestsMade;
    protected ?array $standardRequestHeaders;
    protected array $rateLimiters;
    protected RestExchangeOptions $clientOptions;
    protected RestApiOptions $apiOptions;
    protected Logger $log;

    abstract public function getTimeSyncInfo(): ?TimeSyncInfo;

    abstract public function getTimeOffset(): ?DateTimeContract;

    public function __construct(
        string $baseAddress,
        Logger $log,
        ?Client $httpClient,
        RestExchangeOptions $options,
        RestApiOptions $apiOptions
    ) {
        parent::__construct($baseAddress);
        $this->log = $log;
        $this->requestFactory = new RequestFactory();
        $this->totalRequestsMade = 0;
        $this->clientOptions = $options;
        $this->apiOptions = $apiOptions;
        $this->rateLimiters = $apiOptions->getRateLimiters();
        $this->requestFactory->configure($options->requestTimeout, $httpClient);
    }

    protected function sendRequestAsync(
        Uri $uri,
        string $method,
        \Closure $ct,
        ?array $parameters = null,
        bool $signed = false,
        int $requestWeight = 1,
        ?array $additionalHeaders = null,
        bool $ignoreRatelimit = false
    ): WebCallResult {
        $currentTry = 0;

        while (true) {
            $currentTry++;

            $request = self::prepareRequestAsync($uri, $method, $ct, $parameters, $signed, $requestWeight,
                $additionalHeaders, $ignoreRatelimit);
        }
    }

    protected function prepareRequestAsync(
        Uri $uri,
        string $method,
        \Closure $ct,
        ?array $parameters = null,
        bool $signed = false,
//        string $parameterPosition = null,
//        string $arraySerialization = null,
        int $requestWeight = 1,
        ?array $additionalHeaders = null,
        bool $ignoreRatelimit = false
    ): CallResult {
        // TODO: Implement the method.
    }

    protected function getResponseAsync(
        Request $request,
        \Closure $ct,
        bool $expectedEmptyResponse
    ): WebCallResult {
    }

    protected function constructRequest(
        UriInterface $uri,
        string $method,
        ?array $parameters,
        bool $signed,
        int $requestId,
        ?array $additionalHeaders
    ): Request {
        // TODO: Implement the method.
    }

    protected function validateJson(string $data): WebCallResult
    {
        // TODO: Implement the method.
    }

    protected function syncTimeAsync(): WebCallResult
    {
        // TODO: Implement the method.
    }
}

