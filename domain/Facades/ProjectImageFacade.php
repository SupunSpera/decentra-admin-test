<?php

namespace domain\Facades;

use domain\Services\ProjectImageService;
use Illuminate\Support\Facades\Facade;

class ProjectImageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
       return ProjectImageService::class;
    }
}
