<?php

namespace Msc\MscCryptoExchange\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Msc\MscCryptoExchange\Contracts\DateTimeContract;
use Msc\MscCryptoExchange\Contracts\Request;
use Msc\MscCryptoExchange\Contracts\RestApiClient as RestApiClientAlias;
use Msc\MscCryptoExchange\Helpers\UriHelper;
use Msc\MscCryptoExchange\Objects\CallResult;
use Msc\MscCryptoExchange\Objects\CallResultWithData;
use Msc\MscCryptoExchange\Objects\Enums\HttpMethodParameterPosition;
use Msc\MscCryptoExchange\Objects\Errors\NoApiCredentialsError;
use Msc\MscCryptoExchange\Objects\Errors\ServerError;
use Msc\MscCryptoExchange\Objects\Options\RestApiOptions;
use Msc\MscCryptoExchange\Objects\Options\RestExchangeOptions;
use Msc\MscCryptoExchange\Objects\TimeSyncInfo;
use Msc\MscCryptoExchange\Objects\WebCallResult;
use Msc\MscCryptoExchange\Objects\WebCallResultWithData;
use Msc\MscCryptoExchange\Requests\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

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
     * @var \Illuminate\Support\Facades\Log $log
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

            $request = $this->prepareRequestAsync($uri, $method, $ct, $parameters, $signed, $requestWeight,
                $additionalHeaders, $ignoreRatelimit);

            if ($currentTry > 2) {
                Log::info("Retrying request to ".$uri." (try ".$currentTry.")");
                return new WebCallResult(500, null, null, null, null, null, null, new ServerError('error'),);
            }
            return new WebCallResult($request->getData()->sta, null, null, null, $request->getData(), null, null,
                $request->getError());
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
    ): CallResultWithData {
        $requestId = $this->nextId();

        if ($signed) {
            $syncTask = $this->syncTimeAsync();
            $timeSyncInfo = $this->getTimeSyncInfo();
        }

        if ($signed) {
            Log::warning("[{requestId}] Request {uri.AbsolutePath} failed because no ApiCredentials were provided");
            return new CallResultWithData(error: new NoApiCredentialsError());
        }

        $request = $this->requestFactory->create($method, $uri, $requestId);

        Log::info("[{requestId}] Creating request for ".$uri);

        $result = $this->requestFactory->sendRequestAsync($request, $ct, $requestId, $ignoreRatelimit);
        return new CallResultWithData();
    }

    protected function getResponseAsync(
        Request $request,
        \Closure $ct,
        bool $expectedEmptyResponse
    ): WebCallResult {
    }

//    /**
//     * @throws \Exception
//     */
//    protected function constructRequest(
//        Uri $uri,
//        string $method,
//        ?array $parameters,
//        bool $signed,
//        string $parameterPosition,
//        int $requestId,
//        ?array $additionalHeaders
//    ): Request {
//        $parameters ??= [];
//        $uriParameters = [];
//        $bodyParameters = [];
//        $headers = [];
//        $additionalHeaders ??= [];
//
////        if ($parameterPosition == 'URI') {
////            foreach ($parameters as $key => $value) {
////                $uri = $uri->withQuery($parameters);
////            }
////        }
//
//        $authenticationProvider = $this->authenticationProvider;
//        if ($authenticationProvider != null) {
//            try {
//                $authenticationProvider->authenticateRequest(
//                    $this,
//                    $uri,
//                    $method,
//                    $parameters,
//                    $signed,
//                    $uriParameters,
//                    $bodyParameters,
//                    $headers
//                );
//            } catch (\Exception $ex) {
//                throw new \Exception("Failed to authenticate request, make sure your API credentials are correct", $ex);
//            }
//        }
//    }

    /**
     * Creates a request object
     *
     * @param UriInterface $uri
     * @param string $method
     * @param array|null $parameters
     * @param bool $signed
     * @param HttpMethodParameterPosition $parameterPosition
     * @param array $arraySerialization
     * @param int $requestId
     * @param array|null $additionalHeaders
     * @return RequestInterface
     */
    protected function constructRequest(
        UriInterface $uri,
        string $method,
        ?array $parameters,
        bool $signed,
        HttpMethodParameterPosition $parameterPosition,
        array $arraySerialization,
        int $requestId,
        ?array $additionalHeaders
    ): RequestInterface
    {
        $parameters = $parameters ?? [];
        foreach ($parameters as $key => $value) {
            if ($value instanceof \Closure) {
                $parameters[$key] = $value();
            }
        }

        if ($parameterPosition === HttpMethodParameterPosition::InUri) {
            foreach ($parameters as $key => $value) {
                $uri = UriHelper::addQueryParmeter($uri, $key, (string)$value);
            }
        }

        $headers = [];
        $uriParameters = $parameterPosition === HttpMethodParameterPosition::InUri ? $parameters : [];
        $bodyParameters = $parameterPosition === HttpMethodParameterPosition::InBody ? $parameters : [];

        if ($this->authenticationProvider !== null) {
            try {
                $this->authenticationProvider->authenticateRequest(
                    $this,
                    $uri,
                    $method,
                    $parameters,
                    $signed,
                    $arraySerialization,
                    $parameterPosition,
                    $uriParameters,
                    $bodyParameters,
                    $headers
                );
            } catch (\Exception $ex) {
                throw new \Exception("Failed to authenticate request, make sure your API credentials are correct", 0,
                    $ex);
            }
        }

        // Sanity check
        foreach ($parameters as $key => $value) {
            if (! array_key_exists($key, $uriParameters) && ! array_key_exists($key, $bodyParameters)) {
                throw new \Exception("Missing parameter $key after authentication processing. AuthenticationProvider implementation ".
                    "should return provided parameters in either the uri or body parameters output");
            }
        }

        $uri = UriHelper::setParameters($uri, $uriParameters, $arraySerialization);

        $request = $this->requestFactory->createRequest($method, $uri);
    }

    protected function validateJson(string $data): WebCallResult
    {
        // TODO: Implement the method.
    }

    protected function syncTimeAsync(): WebCallResult
    {
        // TODO: Implement the method.
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    protected function nextId()
    {
        return Str::uuid();
    }
}

