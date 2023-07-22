<?php

namespace Msc\MscCryptoExchange\Contracts;

use Illuminate\Http\Client\Response;

interface Request
{
    /**
     * Accept header
     *
     * @param  string  $value
     * @return void
     */
    public function accept(string $value): void;

    /**
     * Content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Method
     *
     * @param  string  $method
     * @return void
     */
    public function setMethod(string $method): void;

    /**
     * Uri
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Internal request id for tracing
     *
     * @return int
     */
    public function getRequestId(): int;

    /**
     * Set byte content
     *
     * @param  array  $data
     * @return void
     */
    public function setContentBytes(array $data): void;

    /**
     * Set string content
     *
     * @param  string  $data
     * @param  string  $contentType
     * @return void
     */
    public function setContent(string $data, string $contentType): void;

    /**
     * Add a header to the request
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    public function addHeader(string $key, string $value): void;

    /**
     * Get all headers
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get the response
     *
     * @return Response
     */
    public function getResponse(): Response;
}
