<?php

namespace domain\Facades;


use domain\Services\CustomerMilestoneService;
use Illuminate\Support\Facades\Facade;

class CustomerMilestoneFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerMilestoneService::class;
    }
}
