<?php

namespace domain\Facades;

use domain\Services\CustomerDetailService;
use Illuminate\Support\Facades\Facade;

class CustomerDetailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerDetailService::class;
    }
}
