<?php

namespace Msc\MscCryptoExchange\Helpers;

class JsonHelper
{
    /**
     * Decode a JSON string to an associative array
     *
     * @param  string  $json  The JSON string to decode
     * @param  bool  $assoc  When `true`, the returned data will be an associative array
     * @param  int  $depth  The maximum depth of the JSON data
     * @param  int  $options  Bitmask of JSON decode options
     * @return mixed|null The decoded data, or `null` on failure
     */
    public static function decode(string $json, bool $assoc = true, int $depth = 512, int $options = 0)
    {
        return json_decode($json, $assoc, $depth, $options);
    }

    /**
     * Encode data to a JSON string
     *
     * @param  mixed  $data  The data to encode
     * @param  int  $options  Bitmask of JSON encode options
     * @param  int  $depth  The maximum depth of the data
     * @return string|false The JSON string, or `false` on failure
     */
    public static function encode($data, int $options = 0, int $depth = 512)
    {
        return json_encode($data, $options, $depth);
    }
}
