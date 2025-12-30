<?php

namespace domain\Facades;

use domain\Services\URBXWalletRedeemService;
use Illuminate\Support\Facades\Facade;

class URBXWalletRedeemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return URBXWalletRedeemService::class;
    }
}
