<?php

namespace Msc\MscCryptoExchange\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Msc\MscCryptoExchange\Contracts\BaseApiClient as BaseApiClientAlias;

abstract class BaseApiClient implements BaseApiClientAlias
{
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
     * Create a new instance of the base API client.
     *
     * @param string $baseAddress The base address for this API client.
     */
    public function __construct(string $baseAddress)
    {
        $this->baseAddress = $baseAddress;
        $this->httpClient = new Client([
            'base_uri' => $this->baseAddress,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Perform a GET request to the API.
     *
     * @param string $endpoint The API endpoint to request.
     * @param array $queryParams Query parameters for the request.
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get(string $endpoint, array $queryParams = [])
    {
        try {
            $response = $this->httpClient->get($endpoint, ['query' => $queryParams]);
            return $this->handleResponse($response);
        } catch (ClientException | RequestException | GuzzleException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Perform a POST request to the API.
     *
     * @param string $endpoint The API endpoint to request.
     * @param array $data Data to send in the request body.
     * @return mixed
     */
    protected function post(string $endpoint, array $data = [])
    {
        try {
            $response = $this->httpClient->post($endpoint, ['json' => $data]);
            return $this->handleResponse($response);
        } catch (ClientException | RequestException | GuzzleException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Handle the API response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
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
     * @param \GuzzleHttp\Exception\GuzzleException $exception
     * @return mixed
     */
    protected function handleException($exception)
    {
        Log::error($exception->getMessage());
        return null;
    }
}
