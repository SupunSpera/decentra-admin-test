<?php

namespace domain\Facades;

use domain\Services\WalletTransactionService;
use Illuminate\Support\Facades\Facade;

class WalletTransactionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WalletTransactionService::class;
    }
}
