<?php

namespace App\Http\Livewire\Item;

use App\Models\Item;
use domain\Facades\ItemFacade;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ItemDataTable extends LivewireDatatable
{
    public $model = Item::class;

    protected $listeners = ['deleteRecord', 'publishItem', 'unpublishItem'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::name('name')->label('Name'),
            // Column::name('description')->label('Description'),
            Column::callback(['payment_type'], function ($payment_type) {
                return $this->type($payment_type);
            })->label('Type'),
             Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),
            Column::name('price')->label('Price'),
            Column::name('level')->label('Level'),
            Column::raw("DATE_FORMAT(items.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.Items.actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }

    /**
     * type
     *
     * @param  mixed $type
     * @return string
     */
    public function type($type): string
    {
        $data = '<div class="text-center">';
        if ($type == Item::PAYMENT_TYPE['ONE_TIME']) {
            $data = $data . '<span class="badge badge-success">One Time</span>';
        } else {
            $data = $data . '<span class="badge badge-info">Monthly</span>';
        }
        return $data . '</div>';
    }

     /**
     * status
     *
     * @param  mixed $status
     * @return string
     */
    public function status($status): string
    {
        $data = '<div class="text-center">';
        if ($status == Item::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == Item::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }


    /**
     * publishItem
     *
     * @param  mixed $productId
     * @return void
     */
    function publishItem(int $productId)
    {
        $product = ItemFacade::get($productId);

        if ($product->image) {
            $response = ItemFacade::update($product, array('status' => Item::STATUS['PUBLISHED']));


            if ($response) {
                Session::flash('alert-success', 'Item unpublished successfully');
                return redirect()->route('items.all')->with($response);
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('items.all')->with($response);
            }
        } else {
            Session::flash('alert-warning', 'Update Image Before Publish!');
            return redirect()->route('items.all');
        }
    }


    /**
     * unpublishItem
     *
     * @param  mixed $productId
     * @return void
     */
    function unpublishItem(int $productId)
    {

        $product = ItemFacade::get($productId);
        $response = ItemFacade::update($product, array('status' => Item::STATUS['DRAFT']));


        if ($response) {
            Session::flash('alert-success', 'Item unpublished successfully');
            return redirect()->route('items.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('items.all')->with($response);
        }
    }


     /**
     * deleteRecord
     *
     * @param  mixed $productId
     * @return void
     */
    function deleteRecord(int $productId)
    {
        $product= ItemFacade::get($productId);
        $response = ItemFacade::delete($product);


        if ($response ) {
            Session::flash('alert-success', 'Item deleted successfully');
            return redirect()->route('items.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('items.all')->with($response);
        }
    }
}
