<?php

namespace App\Http\Livewire\Wallet;

// use App\Exports\Customer\CustomerExport;
use App\Models\Customer;
use App\Models\FrozenToken;
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
class FrozenTokenDataTable extends LivewireDatatable
{
    public $model = FrozenToken::class;
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
        return FrozenToken::query()
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
            Column::callback(['status'], function ($type) {
                return $this->getType($type);
            })->label('Status'),
            Column::raw("DATE_FORMAT(frozen_tokens.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
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
        if ($type == FrozenToken::STATUS['FROZEN']) {
            $data = $data . '<span class="badge badge-warning">Frozen</span>';
        } else if ($type == FrozenToken::STATUS['UNFREEZE']) {
            $data = $data . '<span class="badge badge-success">Unfreeze</span>';
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
