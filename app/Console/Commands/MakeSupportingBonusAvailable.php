<?php

namespace App\Console\Commands;

use domain\Facades\CustomerSupportingBonusFacade;
use Illuminate\Console\Command;

class MakeSupportingBonusAvailable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:makeSupportingBonusAvailable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make supporting bonus available';

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
        CustomerSupportingBonusFacade::makeSupportingBonusAvailable();
        return 0;
    }
}
