<?php

namespace App\Http\Controllers;

use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrbxRedeemController extends Controller
{

    /**
     * pending
     *
     * @return void
     */
    public function pending()
    {

        return view('pages.urbx.all-redeems');
    }


    // /**
    //  * approved
    //  *
    //  * @return void
    //  */
    // public function approved()
    // {

    //     return view('pages.withdrawals.approved');
    // }


    // /**
    //  * sent
    //  *
    //  * @return void
    //  */
    // public function sent()
    // {

    //     return view('pages.withdrawals.sent');
    // }

    // /**
    //  * sent
    //  *
    //  * @return void
    //  */
    // public function rejected()
    // {

    //     return view('pages.withdrawals.rejected');
    // }

    // /**
    //  * confirmWithdraw
    //  *
    //  * @param  mixed $request
    //  * @return void
    //  */
    // public function confirmWithdraw(Request $request)
    // {

    //     if ($request->status == "success") { // if withdrawal success

    //         $redeemId = $request->redeem_id;

    //         DB::beginTransaction();

    //         try {

    //             $walletRedeem = WalletRedeemFacade::get($redeemId);

    //             if ($walletRedeem) {

    //                 // update wallet redeem and wallet transaction
    //                 if ($walletRedeem->status == WalletRedeem::STATUS['WITHDRAWAL_PENDING']) {
    //                     $walletTransaction = WalletTransactionFacade::get($walletRedeem->wallet_transaction_id);
    //                     $withdrawalFeeTransaction = WalletTransactionFacade::get($walletRedeem->withdrawal_fee_transaction_id);
    //                     $adminFeeTransaction = WalletTransactionFacade::get($walletRedeem->admin_fee_transaction_id);

    //                     $totalAmount =  $walletRedeem->amount + $withdrawalFeeTransaction->usdt_amount;

    //                     // update wallet transaction
    //                     WalletTransactionFacade::update(
    //                         $walletTransaction,
    //                         array(
    //                             'status' => WalletTransaction::STATUS['SUCCESS']
    //                         )
    //                     );

    //                     // update withdrawal fee transaction
    //                     WalletTransactionFacade::update(
    //                         $withdrawalFeeTransaction,
    //                         array(
    //                             'status' => WalletTransaction::STATUS['SUCCESS']
    //                         )
    //                     );

    //                     // update admin fee transaction
    //                     WalletTransactionFacade::update(
    //                         $adminFeeTransaction,
    //                         array(
    //                             'status' => WalletTransaction::STATUS['SUCCESS']
    //                         )
    //                     );

    //                     // update wallet redeem
    //                     WalletRedeemFacade::update($walletRedeem, array(
    //                         'status' => WalletRedeem::STATUS['SENT']
    //                     ));
    //                 }
    //             }



    //             DB::commit();
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             // throw $e; // Re-throw the exception for handling

    //         }
    //     } else if ($request->status == "failed") { // if withdrawal failed
    //         $redeemId = $request->redeem_id;


    //         DB::beginTransaction();

    //         try {

    //             $walletRedeem = WalletRedeemFacade::get($redeemId);

    //             if ($walletRedeem) {

    //                 // update wallet redeem and return withdrawal amount back to customer's wallet
    //                 $walletTransaction = WalletTransactionFacade::get($walletRedeem->wallet_transaction_id);
    //                 $withdrawalFeeTransaction = WalletTransactionFacade::get($walletRedeem->withdrawal_fee_transaction_id);
    //                 $adminFeeTransaction = WalletTransactionFacade::get($walletRedeem->admin_fee_transaction_id);

    //                 $wallet = WalletFacade::get($walletRedeem->wallet_id);

    //                 $withdrawalFeeWallet =  WalletFacade::get(config('settings.fee_wallet'));

    //                 $totalAmount =  $walletRedeem->amount + $withdrawalFeeTransaction->usdt_amount;

    //                 // update customers wallet
    //                 WalletFacade::update(
    //                     $wallet,
    //                     array(
    //                         'usdt_amount' => $wallet->usdt_amount + $totalAmount
    //                     )
    //                 );

    //                 // update withdrawal fee wallet
    //                 WalletFacade::update(
    //                     $withdrawalFeeWallet,
    //                     array(
    //                         'usdt_amount' => $wallet->usdt_amount - $withdrawalFeeTransaction->usdt_amount
    //                     )
    //                 );

    //                 // update wallet transaction
    //                 WalletTransactionFacade::update(
    //                     $walletTransaction,
    //                     array(
    //                         'status' => WalletTransaction::STATUS['CANCELLED']
    //                     )
    //                 );

    //                 // update withdrawal fee transaction
    //                 WalletTransactionFacade::update(
    //                     $withdrawalFeeTransaction,
    //                     array(
    //                         'status' => WalletTransaction::STATUS['CANCELLED']
    //                     )
    //                 );

    //                 // update admin fee transaction
    //                 WalletTransactionFacade::update(
    //                     $adminFeeTransaction,
    //                     array(
    //                         'status' => WalletTransaction::STATUS['CANCELLED']
    //                     )
    //                 );


    //                 WalletRedeemFacade::update($walletRedeem, array(
    //                     'status' => WalletRedeem::STATUS['REJECTED']
    //                 ));
    //             }

    //             DB::commit();
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             // throw $e; // Re-throw the exception for handling

    //         }
    //     }
    // }
}
