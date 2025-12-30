<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use domain\Facades\ProjectFacade;
use Illuminate\Console\Command;

class CompleteProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:completeProjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'completeProjects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $projects = ProjectFacade::completeProjects(Carbon::today());
        return 0;
    }
}
