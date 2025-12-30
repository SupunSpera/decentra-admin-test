<?php

namespace domain\Facades;

use domain\Services\ItemDirectCommissionService;
use Illuminate\Support\Facades\Facade;

class ItemDirectCommissionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemDirectCommissionService::class;
    }
}
