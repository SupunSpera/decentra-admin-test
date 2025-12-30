<?php

namespace domain\Facades;

use domain\Services\ItemPurchaseService;
use Illuminate\Support\Facades\Facade;

class ItemPurchaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemPurchaseService::class;
    }
}
