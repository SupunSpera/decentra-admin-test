<?php

namespace App\Http\Livewire;

use App\Models\GeneratedSupportingBonus;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GeneratedBonusesDataTable extends LivewireDatatable
{
    public $model = GeneratedSupportingBonus::class;


    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return GeneratedSupportingBonus::query()
            ->leftJoin('customers', 'customers.id', 'generated_supporting_bonuses.customer_id');
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
            Column::name('share_amount')->label('Share Amount'),
            Column::name('share_value')->label('Share Value'),
            Column::name('commission')->label('Commission'),
            Column::raw("DATE_FORMAT(generated_supporting_bonuses.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
        ];
    }
}
