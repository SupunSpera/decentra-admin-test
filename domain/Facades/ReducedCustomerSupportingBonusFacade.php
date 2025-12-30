<?php

namespace domain\Facades;

use domain\Services\ReducedCustomerSupportingBonusService;
use Illuminate\Support\Facades\Facade;

class ReducedCustomerSupportingBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ReducedCustomerSupportingBonusService::class;
    }
}
