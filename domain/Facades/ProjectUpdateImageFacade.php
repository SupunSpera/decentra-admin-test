<?php

namespace domain\Facades;

use domain\Services\ProjectUpdateImageService;
use Illuminate\Support\Facades\Facade;

class ProjectUpdateImageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
       return ProjectUpdateImageService::class;
    }
}
