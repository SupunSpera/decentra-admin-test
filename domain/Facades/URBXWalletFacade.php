<?php

namespace domain\Facades;

use domain\Services\URBXWalletService;
use Illuminate\Support\Facades\Facade;

class URBXWalletFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return URBXWalletService::class;
    }
}
