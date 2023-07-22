<?php

namespace Msc\MscCryptoExchange\Requests;

use App\Interfaces\IRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Request implements IRequest
{
    private $request;
    private $httpClient;
    private $content;

    public function __construct(string $method, string $uri, int $requestId)
    {
        $this->request = Http::withHeaders(['RequestId' => $requestId]);
        $this->request->method($method);
        $this->request->uri($uri);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function accept(string $value): void
    {
        $this->request->accept($value);
    }

    public function setMethod(string $method): void
    {
        $this->request->method($method);
    }

    public function getUri(): string
    {
        return $this->request->uri();
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
