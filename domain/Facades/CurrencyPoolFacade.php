<?php

namespace domain\Facades;

use domain\Services\CurrencyPoolService;
use Illuminate\Support\Facades\Facade;

class CurrencyPoolFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CurrencyPoolService::class;
    }
}
