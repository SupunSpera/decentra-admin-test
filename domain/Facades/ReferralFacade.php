<?php

namespace domain\Facades;

use domain\Services\ReferralService;
use Illuminate\Support\Facades\Facade;

class ReferralFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ReferralService::class;
    }
}
