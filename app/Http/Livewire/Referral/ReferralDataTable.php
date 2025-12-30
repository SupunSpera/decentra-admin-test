<?php

namespace App\Http\Livewire;

use App\Models\Referral;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ReferralDataTable extends LivewireDatatable
{
    public $model = Referral::class;

    protected $listeners = ['deleteRecord'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::callback(['first_name', 'last_name'], function ($first_name, $last_name) {
                return $first_name . ' ' . $last_name;
            })->label('Name')->searchable(),
            Column::name('email')->label('Email'),
            Column::name('referral_count')->label('Referral Count'),
            Column::raw("DATE_FORMAT(customers.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            // Column::callback(['id'], function ($id) {
            //     return view('pages.customers.actions', ['id' => $id]);
            // })->label('Actions'),
        ];
    }
    // /**
    //  * export
    //  *
    //  * @return void
    //  */
    // public function export()
    // {
    //     return Excel::download(new CustomerExport, 'customers-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx');
    // }

    // /**
    //  * deleteRecord
    //  *
    //  * @param  mixed $customerId
    //  * @return void
    //  */
    // function deleteRecord(int $customerId)
    // {
    //     $response = CustomerFacade::deleteById($customerId);

    //     if ($response == 1) {
    //         Session::flash('alert-success', 'Customer deleted successfully');
    //         return redirect()->route('customers.all')->with($response);
    //     } else {
    //         Session::flash('alert-danger', 'Something went wrong!');
    //         return redirect()->route('customers.all')->with($response);
    //     }
    // }
}
