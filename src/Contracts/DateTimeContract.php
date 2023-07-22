<?php

namespace Msc\MscCryptoExchange\Contracts;

interface DateTimeContract
{
    /**
     * Formats the date according to the specified format.
     *
     * @param  string  $format  The format to use for the output.
     * @return string The formatted date string.
     */
    public function format($format): string;

    /**
     * Returns the timezone of the DateTimeInterface object.
     *
     * @return \DateTimeZone|false Returns a DateTimeZone object on success or false on failure.
     */
    public function getTimezone(): bool|\DateTimeZone;

    /**
     * Returns the Unix timestamp representing the date.
     *
     * @return int The Unix timestamp representing the date.
     */
    public function getTimestamp(): int;
}
