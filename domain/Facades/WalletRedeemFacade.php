<?php

namespace domain\Facades;

use domain\Services\WalletRedeemService;
use Illuminate\Support\Facades\Facade;

class WalletRedeemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WalletRedeemService::class;
    }
}
