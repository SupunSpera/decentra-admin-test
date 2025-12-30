<?php

namespace domain\Facades;

use domain\Services\CustomerPurchaseService;
use Illuminate\Support\Facades\Facade;

class CustomerPurchaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerPurchaseService::class;
    }
}
