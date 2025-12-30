<?php

namespace domain\Facades;

use domain\Services\ProjectInvestmentService;
use Illuminate\Support\Facades\Facade;

class ProjectInvestmentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProjectInvestmentService::class;
    }
}
