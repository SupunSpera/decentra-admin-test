<?php

namespace domain\Facades;

use domain\Services\ProjectService;
use Illuminate\Support\Facades\Facade;

class ProjectFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
       return ProjectService::class;
    }
}
