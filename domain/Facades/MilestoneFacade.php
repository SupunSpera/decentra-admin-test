<?php

namespace domain\Facades;

use domain\Services\MilestoneService;
use Illuminate\Support\Facades\Facade;

class MilestoneFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MilestoneService::class;
    }
}
