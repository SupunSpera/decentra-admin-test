<?php

namespace App\Console\Commands;

use domain\Facades\CustomerMilestoneFacade;
use domain\Services\CustomerMilestoneService;
use Illuminate\Console\Command;

class ArchiveMilestones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:archiveMilestones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        CustomerMilestoneFacade::archiveMilestones();
        return 0;
    }
}
