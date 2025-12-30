<?php

namespace App\Http\Livewire\Withdrawal;

use App\Models\Wallet;
use App\Models\WalletRedeem;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Services\WalletRedeemService;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class SentWithdrawalsDataTable extends LivewireDatatable
{
    public $status;
    public $model = WalletRedeem::class;

    protected $listeners = ['approveWithdrawal'];

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
            ->where('wallet_redeems.status',WalletRedeem::STATUS['SENT']);
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
        }else if ($type == WalletRedeem::STATUS['SENT']) {
            $data = $data . '<span class="badge badge-success">Sent</span>';
        }
        return $data . '</div>';
    }

    public function approveWithdrawal($id) {

        $walletRedeem = WalletRedeemFacade::get($id);

        $update = WalletRedeemFacade::update($walletRedeem, array(
            'status' => WalletRedeem::STATUS['APPROVED']
        ));

        if($update){
            Session::flash('alert-success', 'Withdrawal approved successfully');
            return redirect()->route('withdrawals.all');
        }
    }
}
