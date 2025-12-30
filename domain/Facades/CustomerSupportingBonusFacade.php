<?php

namespace domain\Facades;

use domain\Services\CustomerSupportingBonusService;
use Illuminate\Support\Facades\Facade;

class CustomerSupportingBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerSupportingBonusService::class;
    }
}
