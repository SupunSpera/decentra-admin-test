<?php

namespace domain\Facades;

use domain\Services\DailyShareCalculationService;
use Illuminate\Support\Facades\Facade;

class DailyShareCalculationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DailyShareCalculationService::class;
    }
}
