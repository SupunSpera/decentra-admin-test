<?php

namespace domain\Facades;

use domain\Services\ConnectedCustomerService;
use Illuminate\Support\Facades\Facade;

class ConnectedCustomerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
       return ConnectedCustomerService::class;
    }
}

