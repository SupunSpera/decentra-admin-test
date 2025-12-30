<?php

namespace domain\Facades;

use domain\Services\ProjectUpdateService;
use Illuminate\Support\Facades\Facade;

class ProjectUpdateFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProjectUpdateService::class;
    }
}
