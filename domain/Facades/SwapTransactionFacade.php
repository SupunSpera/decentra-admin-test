<?php

namespace domain\Facades;

use domain\Services\SwapTransactionService;
use Illuminate\Support\Facades\Facade;

class SwapTransactionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SwapTransactionService::class;
    }
}
