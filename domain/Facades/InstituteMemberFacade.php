<?php

namespace domain\Facades;

use domain\Services\InstituteMemberService;
use Illuminate\Support\Facades\Facade;

class InstituteMemberFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InstituteMemberService::class;
    }
}
