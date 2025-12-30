<?php

namespace domain\Facades;

use domain\Services\WalletDepositService;
use Illuminate\Support\Facades\Facade;

class WalletDepositFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WalletDepositService::class;
    }
}
