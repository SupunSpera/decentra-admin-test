<?php

namespace App\Http\Livewire;

use App\Models\DailyShareCalculation;
use domain\Facades\DailyShareCalculationFacade;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class DailyShareCalculationDataTable extends LivewireDatatable
{
    public $model = DailyShareCalculation::class;


    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return DailyShareCalculation::query()->orderBy('id', 'asc');

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::raw("DATE_FORMAT(daily_share_calculations.created_at, '%d-%b') AS Created_At")->label('Date'),
             Column::name('total_sales')->label('Total Sales'),
             Column::name('total_point')->label('Total Points'),
             Column::name('point_value_bte')->label('Point Value (URBX)'),
             Column::name('binary_pool_bte')->label('Binary Pool (URBX)'),
             Column::name('qualified_shares')->label('Qualified Shares'),
             Column::name('real_share_value')->label('Real Share Value'),
             Column::name('system_share_value')->label('System Share Value'),
             Column::name('payout')->label('Payout'),
             Column::name('cumulative_pool_balance')->label('Pool Balance'),

        ];
    }

      /**
     * getStatus
     *
     * @param  mixed $type
     * @return string
     */
    public function getType($type): string
    {
        $data = '<div class="text-center">';
        if ($type == DailyShareCalculation::TYPE['NEGATIVE']) {
            $data = $data . '<span class="badge badge-warning">Negative</span>';
        } else if ($type == DailyShareCalculation::TYPE['POSITIVE']) {
            $data = $data . '<span class="badge badge-success">Positive</span>';
        }
        return $data . '</div>';
    }


}
