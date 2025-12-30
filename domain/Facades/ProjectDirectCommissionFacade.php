<?php

namespace domain\Facades;

use domain\Services\ProjectDirectCommissionService;
use Illuminate\Support\Facades\Facade;

class ProjectDirectCommissionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProjectDirectCommissionService::class;
    }
}
