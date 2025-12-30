<?php

namespace domain\Facades;

use domain\Services\InstituteWithdrawalApprovalService;
use Illuminate\Support\Facades\Facade;

class InstituteWithdrawalApprovalFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InstituteWithdrawalApprovalService::class;
    }
}
