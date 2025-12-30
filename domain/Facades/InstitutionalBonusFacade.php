<?php

namespace domain\Facades;

use domain\Services\InstitutionalBonusService;
use Illuminate\Support\Facades\Facade;

class InstitutionalBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InstitutionalBonusService::class;
    }
}
