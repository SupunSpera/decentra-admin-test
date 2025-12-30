<?php

namespace App\Console\Commands;

use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\Gift\NfcCustomerFacade;
use Illuminate\Console\Command;

class MakeNfcCardCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:makeNfcCardCustomer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Nfc Card Customer';

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
        NfcCustomerFacade::makeNfcCardCustomers();
        return 0;
    }
}
