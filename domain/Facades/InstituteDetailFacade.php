<?php

namespace domain\Facades;

use domain\Services\InstituteDetailService;
use Illuminate\Support\Facades\Facade;

class InstituteDetailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InstituteDetailService::class;
    }
}
