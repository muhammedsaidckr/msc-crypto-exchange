<?php

namespace Msc\MscCryptoExchange\Objects;

class WebCallResultWithData extends CallResultWithData
{
    protected $responseStatusCode;
    protected $responseHeaders;
    protected $responseTime;
    protected $responseLength;
    protected $requestUrl;
    protected $requestBody;
    protected $requestMethod;
    protected $requestHeaders;

    public function __construct(
        $responseStatusCode = null,
        $responseHeaders = null,
        $responseTime = null,
        $responseLength = null,
        $originalData = null,
        $requestUrl = null,
        $requestBody = null,
        $requestMethod = null,
        $requestHeaders = null,
        $data = null,
        $error = null
    ) {
        parent::__construct($data, $originalData, $error);
        $this->responseStatusCode = $responseStatusCode;
        $this->responseHeaders = $responseHeaders;
        $this->responseTime = $responseTime;
        $this->responseLength = $responseLength;
        $this->requestUrl = $requestUrl;
        $this->requestBody = $requestBody;
        $this->requestMethod = $requestMethod;
        $this->requestHeaders = $requestHeaders;
    }

    public function getResponseStatusCode()
    {
        return $this->responseStatusCode;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    public function getResponseTime()
    {
        return $this->responseTime;
    }

    public function getResponseLength()
    {
        return $this->responseLength;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function getRequestBody()
    {
        return $this->requestBody;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    public function as($data)
    {
        return new WebCallResultWithData(
            $this->responseStatusCode,
            $this->responseHeaders,
            $this->responseTime,
            $this->responseLength,
            $this->originalData,
            $this->requestUrl,
            $this->requestBody,
            $this->requestMethod,
            $this->requestHeaders,
            $data,
            $this->error
        );
    }

    public function asError(Error $error)
    {
        return new WebCallResultWithData(
            $this->responseStatusCode,
            $this->responseHeaders,
            $this->responseTime,
            $this->responseLength,
            $this->originalData,
            $this->requestUrl,
            $this->requestBody,
            $this->requestMethod,
            $this->requestHeaders,
            null,
            $error
        );
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        return $this->isSuccess();
    }

    public function __toString()
    {
        $result = $this->isSuccess() ? "Success" : "Error: ".$this->getError();
        if (! $this->isSuccess()) {
            $result .= " in ".$this->responseTime;
        }
        return $result;
    }
}
