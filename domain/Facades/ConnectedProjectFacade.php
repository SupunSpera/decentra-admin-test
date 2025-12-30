<?php

namespace domain\Facades;

use domain\Services\ConnectedProjectService;
use Illuminate\Support\Facades\Facade;

class ConnectedProjectFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
       return ConnectedProjectService::class;
    }
}

