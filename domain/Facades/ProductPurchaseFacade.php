<?php

namespace domain\Facades;

use domain\Services\ProductPurchaseService;
use Illuminate\Support\Facades\Facade;

class ProductPurchaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProductPurchaseService::class;
    }
}
