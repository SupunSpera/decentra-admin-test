<?php

namespace domain\Facades;

use domain\Services\CryptoNetworkService;
use Illuminate\Support\Facades\Facade;

class CryptoNetworkFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CryptoNetworkService::class;
    }
}










