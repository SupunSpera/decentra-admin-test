<?php

namespace App\Console\Commands;

use domain\Facades\WalletDepositFacade;
use Illuminate\Console\Command;

class RemoveExpiredDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:removeExpiredDeposits';

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
        $deposits = WalletDepositFacade::getExpiredDeposits();

        if (count($deposits) > 0) {
            foreach ($deposits as $deposit) {
                WalletDepositFacade::delete($deposit);
            }
        }

        return 0;
    }
}
