<?php

namespace Msc\MscCryptoExchange\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Log\Logger;
use Msc\MscCryptoExchange\Contracts\DateTimeContract;
use Msc\MscCryptoExchange\Contracts\Request;
use Msc\MscCryptoExchange\Contracts\RestApiClient as RestApiClientAlias;
use Msc\MscCryptoExchange\Objects\CallResult;
use Msc\MscCryptoExchange\Objects\CallResultWithData;
use Msc\MscCryptoExchange\Objects\Errors\NoApiCredentialsError;
use Msc\MscCryptoExchange\Objects\Options\RestApiOptions;
use Msc\MscCryptoExchange\Objects\Options\RestExchangeOptions;
use Msc\MscCryptoExchange\Objects\TimeSyncInfo;
use Msc\MscCryptoExchange\Objects\WebCallResult;
use Msc\MscCryptoExchange\Requests\RequestFactory;
use Psr\Http\Message\UriInterface;
use Illuminate\Support\Facades\Log;

abstract class RestApiClient extends BaseApiClient implements RestApiClientAlias
{
    protected RequestFactory $requestFactory;
    protected ?int $totalRequestsMade;
    protected ?array $standardRequestHeaders;

    /**
     * @var \Msc\MscCryptoExchange\Objects\Options\RestExchangeOptions $clientOptions
     */
    protected $clientOptions;

    /**
     * @var \Msc\MscCryptoExchange\Objects\Options\RestApiOptions $apiOptions
     */
    protected $apiOptions;

    /**
     * @var \Illuminate\Log\Logger $log
     */
    protected $log;

    abstract public function getTimeSyncInfo(): ?TimeSyncInfo;

    abstract public function getTimeOffset(): ?DateTimeContract;

    public function __construct(
        Log $log,
        ?Client $httpClient,
        string $baseAddress,
        RestExchangeOptions $options,
        RestApiOptions $apiOptions
    ) {
        parent::__construct(
            $log,
            $apiOptions->getOutputOriginalData() ?? $options->outputOriginalData,
            $apiOptions->getApiCredentials() ?? $options->apiCredentials,
            $baseAddress,
            $options,
            $apiOptions);
        $this->log = $log;
        $this->requestFactory = new RequestFactory();
        $this->totalRequestsMade = 0;
        $this->clientOptions = $options;
        $this->apiOptions = $apiOptions;
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
        bool $ignoreRatelimit = true
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
        bool $ignoreRatelimit = true
    ): CallResult {
        $requestId = $this->nextId();

        if ($signed) {
            $syncTask = $this->syncTimeAsync();
            $timeSyncInfo = $this->getTimeSyncInfo();

            if ($timeSyncInfo !== null) {
                // Initially with first request we'll need to wait for the time syncing, if it's not the first request we can just continue
//                $syncTimeResult = $syncTask->wait();
//                if (! $syncTimeResult) {
//                    $this->log->debug("[$requestId] Failed to sync time, aborting request: ".$syncTimeResult->getError());
//                    return $syncTimeResult->asIRequest();
//                }
            }
        }

        if ($signed) {
            $this->log->warning("[{requestId}] Request {uri.AbsolutePath} failed because no ApiCredentials were provided");
            return new CallResult(new NoApiCredentialsError());
        }

        $this->log->info("[{requestId}] Creating request for ".$uri);
        $request = ConstructRequest(uri, method,
                parameters ?.OrderBy(p => p.Key).ToDictionary(p => p.Key, p => p.Value), signed, paramsPosition, arraySerialization ?? this.arraySerialization, requestId, additionalHeaders);

            string ? paramString = "";
            if (paramsPosition == HttpMethodParameterPosition.InBody)
                paramString = $" with request body '{request.Content}'";

            var headers = request.GetHeaders();
            if (headers.Any())
                paramString += " with headers " + string.Join(", ", headers.Select(h => h.Key + $"=[{string.Join(",", h.Value)}]"));

            $totalRequestMode++;
            Log::info("[{$requestId}] Sending {method}{(signed ? " $signed" : "")} request to {request.Uri}{paramString ?? " "}");
            return new CallResultWithData($reqest);
    }

    protected function getResponseAsync(
        Request $request,
        \Closure $ct,
        bool $expectedEmptyResponse
    ): WebCallResult {
    }

    /**
     * @throws \Exception
     */
    protected function constructRequest(
        Uri $uri,
        string $method,
        ?array $parameters,
        bool $signed,
        string $parameterPosition,
        int $requestId,
        ?array $additionalHeaders
    ): Request {
        $parameters ??= [];
        $uriParameters = [];
        $bodyParameters = [];
        $headers = [];
        $additionalHeaders ??= [];

//        if ($parameterPosition == 'URI') {
//            foreach ($parameters as $key => $value) {
//                $uri = $uri->withQuery($parameters);
//            }
//        }

        $authenticationProvider = $this->authenticationProvider;
        if ($authenticationProvider != null) {
            try {
                $authenticationProvider->authenticateRequest(
                    $this,
                    $uri,
                    $method,
                    $parameters,
                    $signed,
                    $uriParameters,
                    $bodyParameters,
                    $headers
                );
            } catch (\Exception $ex) {
                throw new \Exception("Failed to authenticate request, make sure your API credentials are correct", $ex);
            }
        }

        // Sanity check
        foreach ($parameters as $key => $value) {
            if (! isset($uriParameters[$key]) && ! isset($bodyParameters[$key])) {
                throw new \Exception("Missing parameter $key after authentication processing. AuthenticationProvider implementation ".
                    "should return provided parameters in either the uri or body parameters output");
            }
        }

        // Add the auth parameters to the uri, start with a new URI to be able to sort the parameters including the auth parameters
        $uri = $uri->setParameters($uriParameters, $arraySerialization);

        $request = $this->requestFactory->create($method, $uri, $requestId);
        $request->accept = Constants::JSON_CONTENT_HEADER;

        foreach ($headers as $key => $value) {
            $request->withAddedHeader($key, $value);
        }

        foreach ($additionalHeaders as $key => $value) {
            $request->withAddedHeader($key, $value);
        }

        if ($this->standardRequestHeaders != null) {
            foreach ($this->standardRequestHeaders as $key => $value) {
                // Only add it if it isn't overwritten
                if (! isset($additionalHeaders[$key])) {
                    $request->withAddedHeader($key, $value);
                }
            }
        }

        if ($parameterPosition == 'BODY') {
            $contentType = $this->requestBodyFormat == RequestBodyFormat::JSON ? Constants::JSON_CONTENT_HEADER : Constants::FORM_CONTENT_HEADER;
            if (! empty($bodyParameters)) {
                $this->writeParamBody($request, $bodyParameters, $contentType);
            } else {
                $request->setContent($this->requestBodyEmptyContent, $contentType);
            }
        }

        return $request;
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

