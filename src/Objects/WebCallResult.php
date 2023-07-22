<?php

namespace Msc\MscCryptoExchange\Objects;

class WebCallResult extends CallResult
{
    protected $responseStatusCode;
    protected $responseHeaders;
    protected $responseTime;
    protected $requestUrl;
    protected $requestBody;
    protected $requestMethod;
    protected $requestHeaders;

    public function __construct(
        $responseStatusCode,
        $responseHeaders,
        $responseTime,
        $requestUrl,
        $requestBody,
        $requestMethod,
        $requestHeaders,
        $error = null
    ) {
        parent::__construct($error);
        $this->responseStatusCode = $responseStatusCode;
        $this->responseHeaders = $responseHeaders;
        $this->responseTime = $responseTime;
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

    public function asError(Error $error)
    {
        return new WebCallResult(
            $this->responseStatusCode,
            $this->responseHeaders,
            $this->responseTime,
            $this->requestUrl,
            $this->requestBody,
            $this->requestMethod,
            $this->requestHeaders,
            $error
        );
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
