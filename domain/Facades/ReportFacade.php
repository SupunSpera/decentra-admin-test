<?php

namespace domain\Facades;

use domain\Services\ReportService;
use Illuminate\Support\Facades\Facade;

class ReportFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ReportService::class;
    }
}
