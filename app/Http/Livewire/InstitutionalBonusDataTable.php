<?php

namespace App\Http\Livewire;

use App\Models\InstitutionalBonus;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class InstitutionalBonusDataTable extends LivewireDatatable
{
    public $model = InstitutionalBonus::class;


    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return InstitutionalBonus::query()
            ->leftJoin('customers', 'customers.id', 'institutional_bonuses.customer_id');
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
            Column::callback(['customer.email'], function ($email) {
                return $email;
            })->label('Customer')->searchable(),
            Column::name('amount')->label('Amount Count'),
            Column::raw("DATE_FORMAT(institutional_bonuses.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
        ];
    }
}
