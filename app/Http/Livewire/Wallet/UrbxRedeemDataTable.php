<?php

namespace App\Http\Livewire\Wallet;

use App\Models\UrbxWalletRedeem;
use App\Models\Wallet;
use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use App\Notifications\EmailRecipient;
use App\Notifications\UrbxWithdrawalApproved;
use domain\Facades\CustomerFacade;
use domain\Facades\URBXWalletFacade;
use domain\Facades\URBXWalletRedeemFacade;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use domain\Services\WalletRedeemService;
use domain\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class UrbxRedeemDataTable extends LivewireDatatable
{
    public $status;
    public $model = UrbxWalletRedeem::class;

    protected $listeners = ['approveURBXWithdrawal', 'rejectURBXWithdrawal'];

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return UrbxWalletRedeem::query()
            ->leftJoin('urbx_wallets', 'urbx_wallets.id', 'urbx_wallet_redeems.urbx_wallet_id')
            ->leftJoin('customers', 'customers.id', 'urbx_wallets.customer_id')
            ->where('urbx_wallet_redeems.status', UrbxWalletRedeem::STATUS['APPROVED'])
            ->orWhere('urbx_wallet_redeems.status', UrbxWalletRedeem::STATUS['WITHDRAWAL_PENDING'])
            ->orWhere('urbx_wallet_redeems.status', UrbxWalletRedeem::STATUS['REJECTED']);
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
            Column::name('urbx_wallet_redeems.metamask_wallet_address')->label('Wallet Address'),
            Column::callback(['status'], function ($status) {
                return $this->getStatus($status);
            })->label('Status'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.urbx.actions', ['id' => $id, 'status' => $status]);
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
        if ($type == UrbxWalletRedeem::STATUS['PENDING']) {
            $data = $data . '<span class="badge badge-warning">Pending</span>';
        } else if ($type == UrbxWalletRedeem::STATUS['APPROVED']) {
            $data = $data . '<span class="badge badge-info">Approved</span>';
        } else if ($type == UrbxWalletRedeem::STATUS['WITHDRAWAL_PENDING']) {
            $data = $data . '<span class="badge badge-warning">Withdrawal Pending</span>';
        } else if ($type == UrbxWalletRedeem::STATUS['SENT']) {
            $data = $data . '<span class="badge badge-success">Sent</span>';
        } else if ($type == UrbxWalletRedeem::STATUS['REJECTED']) {
            $data = $data . '<span class="badge badge-danger">Rejected</span>';
        }
        return $data . '</div>';
    }


    /**
     * approveURBXWithdrawal
     *
     * @param  mixed $id
     * @return void
     */
    public function approveURBXWithdrawal($id)
    {
        DB::beginTransaction();

        try {

            $walletRedeem = UrbxWalletRedeemFacade::get($id);
            $urbxWallet = URBXWalletFacade::get($walletRedeem->urbx_wallet_id);
            $customer = CustomerFacade::get($urbxWallet->customer_id);

            if (isset($walletRedeem->wallet_transaction_id)) {
                $walletTransaction = WalletTransactionFacade::get($walletRedeem->wallet_transaction_id);
                // update wallet transaction
                WalletTransactionFacade::update(
                    $walletTransaction,
                    array(
                        'status' => WalletTransaction::STATUS['SUCCESS']
                    )
                );
            }

            //  send email to customer
            $emailRecipient = new EmailRecipient($customer->email);
            Notification::send($emailRecipient, new UrbxWithdrawalApproved($walletRedeem, $customer));

            $update = UrbxWalletRedeemFacade::update($walletRedeem, array(
                'status' => UrbxWalletRedeem::STATUS['APPROVED']
            ));

            DB::commit();
            Session::flash('alert-success', 'Withdrawal approved successfully');
            return redirect()->route('urbx-withdrawals.pending');
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e; // Re-throw the exception for handling

        }
    }

    /**
     * rejectURBXWithdrawal
     *
     * @param  mixed $id
     * @return void
     */
    public function rejectURBXWithdrawal($id)
    {

        DB::beginTransaction();

        try {

            $walletRedeem = URBXWalletRedeemFacade::get($id);

            if (isset($walletRedeem->wallet_transaction_id)) {
                $walletTransaction = WalletTransactionFacade::get($walletRedeem->wallet_transaction_id);
                $wallet = WalletFacade::get($walletTransaction->wallet_id);

                // update customers wallet
                WalletFacade::update(
                    $wallet,
                    array(
                        'token_amount' => $wallet->token_amount + $walletRedeem->amount
                    )
                );

                // update wallet transaction
                WalletTransactionFacade::update(
                    $walletTransaction,
                    array(
                        'status' => WalletTransaction::STATUS['CANCELLED']
                    )
                );
            }

            $update = UrbxWalletRedeemFacade::update($walletRedeem, array(
                'status' => UrbxWalletRedeem::STATUS['REJECTED']
            ));


            DB::commit();
            Session::flash('alert-success', 'Withdrawal rejected successfully');
            return redirect()->route('urbx-withdrawals.pending');
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e; // Re-throw the exception for handling

        }
    }
}
