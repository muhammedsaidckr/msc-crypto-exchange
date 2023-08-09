<?php

namespace Msc\MscCryptoExchange\Helpers;

use GuzzleHttp\Psr7\Uri;
use Msc\MscCryptoExchange\Objects\UriBuilder;

class UriHelper
{
    public static function addParameter(&$parameters, $key, $value)
    {
        $parameters[$key] = $value;
    }

    /**
     * Add parameter to URI
     *
     * @param string $uri
     * @param string $name
     * @param string $value
     * @return string
     */
    public static function addQueryParmeter($uri, $name, $value)
    {
        $httpValueCollection = parse_url($uri, PHP_URL_QUERY);

        unset($httpValueCollection[$name]);
        $httpValueCollection[$name] = $value;

        $ub = parse_url($uri);
        $ub['query'] = http_build_query($httpValueCollection);

        return http_build_url($ub);
    }

    /**
     * Create a new uri with the provided parameters as query
     *
     * @param array $parameters
     * @param \Psr\Http\Message\UriInterface $baseUri
     * @param string $arraySerialization
     * @return string
     */
    public static function setParameters($baseUri, $parameters, $arraySerialization)
    {
        $uriBuilder = UriBuilder::build($baseUri);
        $httpValueCollection = \HttpUtility::parseQueryString('');
        foreach ($parameters as $parameter) {
            if (is_array($parameter->getValue())) {
                foreach ($parameter->getValue() as $item) {
                    $httpValueCollection->add($arraySerialization == ArrayParametersSerialization::Array ? $parameter->getKey() . '[]' : $parameter->getKey(), $item);
                }
            } else {
                $httpValueCollection->add($parameter->getKey(), $parameter->getValue());
            }
        }
        $uriBuilder->setQuery($httpValueCollection->toString());
        return $uriBuilder->getUri();
    }

}