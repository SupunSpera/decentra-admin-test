<?php

namespace App\Http\Livewire\Withdrawal;

use App\Models\Wallet;
use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use domain\Services\WalletRedeemService;
use domain\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ApprovedWithdrawalsDataTable extends LivewireDatatable
{
    public $status;
    public $model = WalletRedeem::class;

    protected $listeners = ['sendWithdrawal'];

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
            ->where('wallet_redeems.status', WalletRedeem::STATUS['APPROVED'])
            ->orWhere('wallet_redeems.status', WalletRedeem::STATUS['WITHDRAWAL_PENDING']);
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
        } else if ($type == WalletRedeem::STATUS['WITHDRAWAL_PENDING']) {
            $data = $data . '<span class="badge badge-warning">Withdrawal Pending</span>';
        } else if ($type == WalletRedeem::STATUS['SENT']) {
            $data = $data . '<span class="badge badge-success">Sent</span>';
        }
        return $data . '</div>';
    }

    /**
     * sendWithdrawal
     *
     * @param  mixed $id
     * @return void
     */
    public function sendWithdrawal($id)
    {


        try {


            $withdrawalWalletAddress = config('path.withdrawal_wallet_address');

            $walletRedeem = WalletRedeemFacade::get($id);

            $withdrawalWalletBalance = WalletFacade::getETHWalletBalance($withdrawalWalletAddress);

            if(isset(json_decode($withdrawalWalletBalance['response'])->data)){ // if response received successfully
                $withdrawalWalletBalance = json_decode($withdrawalWalletBalance['response'])->data;


                if($withdrawalWalletBalance < $walletRedeem->amount  ){ // if wallet balance less than withdrawal wallet balance
                    $this->dispatchBrowserEvent('insufficient-tokens');
                }else{

                    WalletRedeemFacade::update($walletRedeem, array(
                        'status' => WalletRedeem::STATUS['WITHDRAWAL_PENDING']
                    ));

                    // send request to make withdrawal
                    WalletRedeemFacade::sendTokenWithdrawRequest($walletRedeem->id,$walletRedeem->wallet_address,$walletRedeem->amount);
                    Session::flash('alert-success', 'Withdrawal request send successfully');
                    return redirect()->route('withdrawals.approved');
                }
            }else{ // if response unsuccess
                Session::flash('alert-danger', 'Some error occurred');
                return redirect()->route('withdrawals.approved');
            }






        } catch (\Exception $e) {

            throw $e; // Re-throw the exception for handling
            Session::flash('alert-danger', 'Some error occurred');
            return redirect()->route('withdrawals.approved');
        }
    }
}
