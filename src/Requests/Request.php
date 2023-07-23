<?php

namespace Msc\MscCryptoExchange\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Msc\MscCryptoExchange\Contracts\Request as RequestContract;

class Request implements RequestContract
{
    private $request;
    private $httpClient;
    private $content;

    public function __construct(string $method, string $uri, int $requestId)
    {
        $this->request = new \GuzzleHttp\Psr7\Request($method, $uri, ['RequestId' => $requestId]);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function accept(string $value): void
    {
        $this->request->accept($value);
    }

    public function getRequestId(): int
    {
        return $this->request->header('RequestId');
    }

    public function setContent(string $data, string $contentType): void
    {
        $this->content = $data;
        $this->request->withHeaders(['Content-Type' => $contentType])->withBody($data);
    }

    public function addHeader(string $key, string $value): void
    {
        $this->request->withHeaders([$key => $value]);
    }

    public function getHeaders(): array
    {
        return $this->request->headers()->all();
    }

    public function setContentBytes(array $data): void
    {
        $this->request->withBody($data);
    }

    public function getResponse(): Response
    {
        return new Response($this->request->send());
    }
}
