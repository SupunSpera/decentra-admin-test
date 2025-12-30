<?php

namespace App\Http\Livewire;

use App\Models\DailyTotalShare;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class AllocatedSharesDataTable extends LivewireDatatable
{
    public $model = DailyTotalShare::class;


    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return DailyTotalShare::query()
            ->leftJoin('customers', 'customers.id', 'daily_total_shares.customer_id');
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
            Column::callback(['customers.email'], function ($email) {
                return $email;
            })->label('Customer')->searchable(),
            Column::name('value')->label('Share Amount'),
            Column::raw("DATE_FORMAT(daily_total_shares.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
        ];
    }
}
