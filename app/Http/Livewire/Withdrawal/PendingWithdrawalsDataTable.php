<?php

namespace App\Http\Livewire\Withdrawal;

use App\Models\Wallet;
use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use domain\Services\WalletRedeemService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingWithdrawalsDataTable extends LivewireDatatable
{
    public $status;
    public $model = WalletRedeem::class;

    protected $listeners = ['approveWithdrawal', 'rejectWithdrawal'];

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return WalletRedeem::query()
            ->leftJoin('wallets', 'wallets.id', 'wallet_redeems.wallet_id')
            ->leftJoin('customers', 'customers.id', 'wallets.customer_id')
            ->where('wallet_redeems.status', WalletRedeem::STATUS['PENDING']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::name('customers.email')->label('Email'),
            Column::name('amount')->label('Amount'),
            Column::name('wallet_address')->label('Wallet Address'),
            Column::callback(['status'], function ($status) {
                return $this->getStatus($status);
            })->label('Status'),
            Column::raw("DATE_FORMAT(wallet_redeems.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.withdrawals.actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }

    /**
     * getStatus
     *
     * @param  mixed $type
     * @return string
     */
    public function getStatus($type): string
    {
        $data = '<div class="text-center">';
        if ($type == WalletRedeem::STATUS['PENDING']) {
            $data = $data . '<span class="badge badge-warning">Pending</span>';
        } else if ($type == WalletRedeem::STATUS['APPROVED']) {
            $data = $data . '<span class="badge badge-info">Approved</span>';
        } else if ($type == WalletRedeem::STATUS['SENT']) {
            $data = $data . '<span class="badge badge-success">Sent</span>';
        }
        return $data . '</div>';
    }

    /**
     * approveWithdrawal
     *
     * @param  mixed $id
     * @return void
     */
    public function approveWithdrawal($id)
    {

        $walletRedeem = WalletRedeemFacade::get($id);

        $update = WalletRedeemFacade::update($walletRedeem, array(
            'status' => WalletRedeem::STATUS['APPROVED']
        ));

        if ($update) {
            Session::flash('alert-success', 'Withdrawal approved successfully');
            return redirect()->route('withdrawals.approved');
        }
    }


    /**
     * rejectWithdrawal
     *
     * @param  mixed $id
     * @return void
     */
    public function rejectWithdrawal($id)
    {

        DB::beginTransaction();

        try {

            $walletRedeem = WalletRedeemFacade::get($id);
            $walletTransaction = WalletTransactionFacade::get($walletRedeem->wallet_transaction_id);
            $withdrawalFeeTransaction = WalletTransactionFacade::get($walletRedeem->withdrawal_fee_transaction_id);
            $adminFeeTransaction = WalletTransactionFacade::get($walletRedeem->admin_fee_transaction_id);

            $wallet = WalletFacade::get($walletRedeem->wallet_id);

            $totalAmount =  $walletRedeem->amount + $withdrawalFeeTransaction->usdt_amount;

            WalletFacade::update(
                $wallet,
                array(
                    'usdt_amount' => $wallet->usdt_amount + $totalAmount
                )
            );

            // update wallet transaction
            WalletTransactionFacade::update(
                $walletTransaction,
                array(
                    'status' => WalletTransaction::STATUS['CANCELLED']
                )
            );

            // update withdrawal fee transaction
            WalletTransactionFacade::update(
                $withdrawalFeeTransaction,
                array(
                    'status' => WalletTransaction::STATUS['CANCELLED']
                )
            );

            // update admin fee transaction
            WalletTransactionFacade::update(
                $adminFeeTransaction,
                array(
                    'status' => WalletTransaction::STATUS['CANCELLED']
                )
            );

            // get withdrawal fee wallet
            $withdrawalFeeWallet =  WalletFacade::get(config('settings.fee_wallet'));

            //add withdrawal fee to the fee wallet
            WalletFacade::update(
                $withdrawalFeeWallet,
                array(
                    'usdt_amount' => $withdrawalFeeWallet->usdt_amount - $withdrawalFeeTransaction->usdt_amount
                )
            );

            WalletRedeemFacade::update($walletRedeem, array(
                'status' => WalletRedeem::STATUS['REJECTED']
            ));

            DB::commit();
            Session::flash('alert-success', 'Withdrawal rejected successfully');
            return redirect()->route('withdrawals.rejected');
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e; // Re-throw the exception for handling
            Session::flash('alert-danger', 'Some error occurred');
            return redirect()->route('withdrawals.pending');
        }


    }
}
