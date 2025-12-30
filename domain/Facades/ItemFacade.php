<?php

namespace domain\Facades;

use domain\Services\ItemService;
use Illuminate\Support\Facades\Facade;

class ItemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemService::class;
    }
}
