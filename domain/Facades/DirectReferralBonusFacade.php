<?php

namespace domain\Facades;

use domain\Services\DirectReferralBonusService;
use Illuminate\Support\Facades\Facade;

class DirectReferralBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DirectReferralBonusService::class;
    }
}
