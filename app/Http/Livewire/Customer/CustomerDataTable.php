<?php

namespace App\Http\Livewire\Customer;

// use App\Exports\Customer\CustomerExport;
use App\Models\Customer;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
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
class CustomerDataTable extends LivewireDatatable
{
    public $model = Customer::class;

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return Customer::query()
            ->where('type', Customer::TYPE['INDIVIDUAL']);
    }

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
            Column::callback(['status'], function ($status) {
                return $this->getStatus($status);
            })->label('Status'),
            Column::raw("DATE_FORMAT(customers.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id'], function ($id) {
                return view('pages.customers.actions', ['id' => $id]);
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
        if ($type == Customer::STATUS['ACTIVE']) {
            $data = $data . '<span class="badge badge-success">Active</span>';
        } else if ($type == Customer::STATUS['PENDING']) {
            $data = $data . '<span class="badge badge-warning">Pending</span>';
        }
        return $data . '</div>';
    }


    /**
     * deleteRecord
     *
     * @param  mixed $customerId
     * @return void
     */
    function deleteRecord(int $customerId)
    {
        $customer = CustomerFacade::get($customerId);
        $response = CustomerFacade::delete($customer);

        if ($response) {
            Session::flash('alert-success', 'Customer deleted successfully');
            return redirect()->route('customers.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('customers.all')->with($response);
        }
    }
}
