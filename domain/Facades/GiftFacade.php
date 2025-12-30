<?php

namespace domain\Facades;

use domain\Services\GiftService;
use Illuminate\Support\Facades\Facade;

class GiftFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GiftService::class;
    }
}
