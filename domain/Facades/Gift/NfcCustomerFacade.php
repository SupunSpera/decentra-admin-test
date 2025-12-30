<?php

namespace domain\Facades\Gift;

use domain\Services\Gift\NfcCustomerService;
use Illuminate\Support\Facades\Facade;

class NfcCustomerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NfcCustomerService::class;
    }
}
