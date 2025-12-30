<?php

namespace App\Http\Livewire\Wallet;

// use App\Exports\Customer\CustomerExport;
use App\Models\Customer;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\WalletFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

/**
 * Customer Data Table
 *
 * php version 8
 *
 * @category Livewire
 * @author   Spera Labs
 * @license  https://decentrax.com Config
 * @link     https://decentrax.com/
 *
 * */
class WalletTransactionDataTable extends LivewireDatatable
{
    public $model = WalletTransaction::class;
    public $wallet,$customer_id;

    protected $listeners = ['refresh'];

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {

        $this->wallet = WalletFacade::getByCustomerId($this->customer_id);
        return WalletTransaction::query()
            ->where('wallet_id',   $this->wallet->id);
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
            Column::callback(['token_amount'], function ($token_amount) {
                return 'URBX '.$token_amount;
            })->label('Token Amount'),
            Column::name('usdt_amount')->label('Usdt Amount'),
            Column::callback(['type'], function ($type) {
                return $this->getType($type);
            })->label('Status'),
            Column::callback(['from'], function ($type) {
                return $this->getDepositType($type);
            })->label('Deposit From'),
            Column::raw("DATE_FORMAT(wallet_transactions.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
        ];
    }

    /**
     * getType
     *
     * @param  mixed $type
     * @return string
     */
    public function getType($type): string
    {
        $data = '<div class="text-center">';
        if ($type == WalletTransaction::TYPE['DEPOSIT']) {
            $data = $data . '<span class="badge badge-success">Deposit</span>';
        } else if ($type == WalletTransaction::TYPE['PURCHASE']) {
            $data = $data . '<span class="badge badge-primary">Purchase</span>';
        }  else if ($type == WalletTransaction::TYPE['SUPPORTING_BONUS']) {
            $data = $data . '<span class="badge badge-secondary">Supporting Bonus</span>';
        } else if ($type == WalletTransaction::TYPE['WITHDRAW']) {
            $data = $data . '<span class="badge badge-warning">Withdraw</span>';
        } else if ($type == WalletTransaction::TYPE['SWAP']) {
            $data = $data . '<span class="badge badge-secondary">Swap</span>';
        } else if ($type == WalletTransaction::TYPE['DIRECT_REFERRAL_BONUS']) {
            $data = $data . '<span class="badge badge-secondary">Direct referral Bonus</span>';
        }else if ($type == WalletTransaction::TYPE['WITHDRAW_FEE']) {
            $data = $data . '<span class="badge badge-dark">Withdrawal Fee</span>';
        }else if ($type == WalletTransaction::TYPE['SWAP_FEE']) {
            $data = $data . '<span class="badge badge-dark">Swap Fee Fee</span>';
        }else if ($type == WalletTransaction::TYPE['ADMIN_FEE']) {
            $data = $data . '<span class="badge badge-dark">Admin Fee Fee</span>';
        }else if ($type == WalletTransaction::TYPE['PROJECT_HARVEST']) {
            $data = $data . '<span class="badge badge-success">Project Harvest</span>';
        }else if ($type == WalletTransaction::TYPE['PROJECT_DIRECT_COMMISSION']) {
            $data = $data . '<span class="badge badge-secondary">Project Direct Commission</span>';
        }else if ($type == WalletTransaction::TYPE['URBX_WITHDRAWAL']) {
            $data = $data .  '<span class="badge badge-warning">URBX Withdrawal</span>';
        }
        return $data . '</div>';
    }

    /**
     * getFrom
     *
     * @param  mixed $type
     * @return
     */
    public function getDepositType($depositFrom)
    {
        $data = '<div class="text-center">';

        if ($depositFrom == WalletTransaction::FROM['CUSTOMER']) {
            $data = $data . '<span class="badge badge-info">Customer</span>';
        } else if ($depositFrom == WalletTransaction::FROM['ADMIN']) {
            $data = $data . '<span class="badge badge-dark">Admin</span>';
        }
        return $data . '</div>';
    }

     /**
     * Refresh the data table.
     *
     * @return void
     */
    public function refresh()
    {
        $this->emit('refreshDataTable');
    }



}
