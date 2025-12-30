<?php

namespace domain\Facades;

use domain\Services\DailyTotalShareService;
use Illuminate\Support\Facades\Facade;

class DailyTotalShareFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DailyTotalShareService::class;
    }
}
