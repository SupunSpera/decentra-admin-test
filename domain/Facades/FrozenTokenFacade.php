<?php

namespace domain\Facades;

use domain\Services\FrozenTokenService;
use Illuminate\Support\Facades\Facade;

class FrozenTokenFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FrozenTokenService::class;
    }
}
