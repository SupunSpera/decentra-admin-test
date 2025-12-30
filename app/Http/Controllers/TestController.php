<?php

namespace App\Http\Controllers;

use App\Models\DailyShareCalculation;
use Illuminate\Http\Request;
use App\Traits\Encrypt\EncryptHelper;
use domain\Facades\CustomerFacade;
use domain\Facades\DailyShareCalculationFacade;
use domain\Facades\WalletFacade;

class TestController extends Controller
{
    use EncryptHelper;

    /**
     * Test method
     *
     * @return array
     */
    public function test()
    {


        // $dailyCalculatedShares = DailyShareCalculationFacade::all();
        // // $lastCalculatedShare = DailyShareCalculationFacade::getLastRecord();



        // foreach($dailyCalculatedShares as $share){

        //     if($share->id > 1){
        //         $previousRecord = DailyShareCalculationFacade::get(($share->id -1));

        //         $lastCumulativeBalance =  $previousRecord->cumulative_pool_balance;

        //         if($share->type == DailyShareCalculation::TYPE['NEGATIVE']){ // if this pool balance is negative value
        //             $cumulativePoolBalance = $lastCumulativeBalance - $share->pool_balance;
        //         }else{
        //             $cumulativePoolBalance = $lastCumulativeBalance + $share->pool_balance;
        //         }

        //         DailyShareCalculationFacade::update($share, array('cumulative_pool_balance'=>$cumulativePoolBalance ) );

        //     }



        // }
        // $ethWallet = WalletFacade::createETHWallet();

        // $data = json_decode($ethWallet['response']);

        // $wallet = WalletFacade::get(1);

        // $encrypted = $this->custom_encrypt($data->data->privateKey);
        // WalletFacade::update($wallet,array('eth_wallet_address'=>$data->data->address,'eth_wallet_private_key'=>$encrypted));



        // // foreach ($wallets as $wallet) { privateKey
        //
        // //     $decrypted = $this->custom_decrypt($wallet->row_key);
        //     dump($encrypted);
        //
        // // }


        // $wallet = WalletFacade::get(1);
        // $decrypted = $this->custom_decrypt($wallet->eth_wallet_private_key);





    }
}
