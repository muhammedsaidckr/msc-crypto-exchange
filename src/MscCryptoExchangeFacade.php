<?php

namespace Msc\MscCryptoExchange;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Msc\MscCryptoExchange\Skeleton\SkeletonClass
 */
class MscCryptoExchangeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'msc-crypto-exchange';
    }
}
