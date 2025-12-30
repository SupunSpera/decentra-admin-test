<?php

namespace domain\Facades;

use domain\Services\CustomerGiftService;
use domain\Services\CustomerMilestoneService;
use Illuminate\Support\Facades\Facade;

class CustomerGiftFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerGiftService::class;
    }
}
