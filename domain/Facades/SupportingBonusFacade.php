<?php

namespace domain\Facades;

use domain\Services\SupportingBonusService;
use Illuminate\Support\Facades\Facade;

class SupportingBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SupportingBonusService::class;
    }

}
