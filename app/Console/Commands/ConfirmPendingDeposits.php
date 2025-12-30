<?php

namespace App\Console\Commands;

use domain\Facades\WalletDepositFacade;
use domain\Facades\WalletFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Encrypt\EncryptHelper;

class ConfirmPendingDeposits extends Command
{

    use EncryptHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:confirmPendingDeposits';

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
        $deposits = WalletDepositFacade::all();


        if (count($deposits) > 0) {

            foreach ($deposits as $deposit) {

                $wallet = WalletFacade::getByCustomerId($deposit->customer_id);
                WalletDepositFacade::sendTokenTransferRequest($wallet->eth_wallet_address, $this->custom_decrypt($wallet->eth_wallet_private_key));
            }
        }

        return 0;
    }
}
