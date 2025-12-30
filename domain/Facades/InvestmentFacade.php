<?php

namespace domain\Facades;

use domain\Services\InvestmentService;
use Illuminate\Support\Facades\Facade;

class InvestmentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InvestmentService::class;
    }
}
