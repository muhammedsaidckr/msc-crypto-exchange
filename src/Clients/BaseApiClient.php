<?php

namespace Msc\MscCryptoExchange\Clients;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Log;
use Msc\MscCryptoExchange\Authentication\ApiCredentials;
use Msc\MscCryptoExchange\Contracts\BaseApiClient as BaseApiClientAlias;
use Msc\MscCryptoExchange\Helpers\JsonHelper;
use Msc\MscCryptoExchange\Objects\CallResult;
use Msc\MscCryptoExchange\Objects\CallResultWithData;
use Msc\MscCryptoExchange\Objects\Errors\DeserializeError;
use Msc\MscCryptoExchange\Objects\Options\ApiOptions;
use Msc\MscCryptoExchange\Objects\Options\ExchangeOptions;

abstract class BaseApiClient implements BaseApiClientAlias
{
    protected $log;

    /**
     * The base address for this API client
     *
     * @var string
     */
    protected $baseAddress;

    /**
     * The HTTP client instance used for making requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \Msc\MscCryptoExchange\Authentication\AuthenticationProvider
     */
    protected $authenticationProvider;

    /**
     * @var \Msc\MscCryptoExchange\Objects\Options\RestApiOptions $apiOptions
     */
    protected $apiOptions;

    /**
     * @var \Msc\MscCryptoExchange\Objects\Options\ExchangeOptions $clientOptions
     */
    protected $clientOptions;

    /**
     * @var bool $outputOriginalData
     */
    public bool $outputOriginalData;

    /**
     * Create a new instance of the base API client.
     *
     * @param  string  $baseAddress  The base address for this API client.
     */
    public function __construct(
        Log $log,
        bool $outputOriginalData,
        ?ApiCredentials $apiCredentials,
        string $baseAddress,
        ExchangeOptions $clientOptions,
        ApiOptions $apiOptions
    ) {
        $this->log = $log;
        $this->clientOptions = $clientOptions;
        $this->apiOptions = $apiOptions;
        $this->outputOriginalData = $outputOriginalData;
        $this->baseAddress = $baseAddress;
//        ]);
        $this->authenticationProvider = self::createAuthenticationProvider($apiCredentials);
    }

    /**
     * @param  \Msc\MscCryptoExchange\Authentication\ApiCredentials  $credentials
     * @return mixed
     */
    protected abstract function createAuthenticationProvider(ApiCredentials $credentials);

    /**
     * Deserialize a stream into an object
     *
     * @param $stream
     * @param  int|null  $requestId
     * @param  int|null  $elapsedMilliseconds
     * @return CallResult
     */
    protected function deserialize($stream, ?int $requestId = null, ?int $elapsedMilliseconds = null): CallResult
    {
        $serializer = $this->defaultSerializer();
        $data = null;

        try {
            $reader = new Stream($stream);
            if ($this->outputOriginalData) {
                $data = $reader->read($reader->getSize());
                $this->log->debug("Response recived: ".! is_null($elapsedMilliseconds) ? "in $elapsedMilliseconds" : "Original data: $data");
                return new CallResultWithData(json_decode($data), $data);
            }

            return new CallResultWithData(json_decode($data));
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            return new CallResult(new DeserializeError("Deserialize unknown exception: ".$e->getMessage(), $data));
        }
    }

    /**
     * Perform a GET request to the API.
     *
     * @param  string  $endpoint  The API endpoint to request.
     * @param  array  $queryParams  Query parameters for the request.
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get(string $endpoint, array $queryParams = [])
    {
        try {
            $response = $this->httpClient->get($endpoint, ['query' => $queryParams]);
            return $this->handleResponse($response);
        } catch (ClientException|RequestException|GuzzleException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Perform a POST request to the API.
     *
     * @param  string  $endpoint  The API endpoint to request.
     * @param  array  $data  Data to send in the request body.
     * @return mixed
     */
    protected function post(string $endpoint, array $data = [])
    {
        try {
            $response = $this->httpClient->post($endpoint, ['json' => $data]);
            return $this->handleResponse($response);
        } catch (ClientException|RequestException|GuzzleException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Handle the API response.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return mixed
     */
    protected function handleResponse($response)
    {
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    /**
     * Handle API exceptions.
     *
     * @param  \GuzzleHttp\Exception\GuzzleException  $exception
     * @return mixed
     */
    protected function handleException($exception)
    {
        Log::error($exception->getMessage());
        return null;
    }

    private static function defaultSerializer()
    {
        return JsonHelper::encode([
            "dateTimeZoneHandling" => 'UTC',
            "culture" => app()->getLocale(),
        ]);
    }
}
