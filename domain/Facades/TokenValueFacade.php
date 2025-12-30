<?php

namespace domain\Facades;

use domain\Services\TokenValueService;
use Illuminate\Support\Facades\Facade;

class TokenValueFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TokenValueService::class;
    }
}
