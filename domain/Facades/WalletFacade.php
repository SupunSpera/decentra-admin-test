<?php

namespace domain\Facades;

use domain\Services\WalletService;
use Illuminate\Support\Facades\Facade;

class WalletFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WalletService::class;
    }
}
