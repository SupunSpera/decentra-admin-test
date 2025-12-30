<?php

namespace domain\Facades;

use domain\Services\GeneratedSupportingBonusService;
use Illuminate\Support\Facades\Facade;

class GeneratedSupportingBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GeneratedSupportingBonusService::class;
    }

}
