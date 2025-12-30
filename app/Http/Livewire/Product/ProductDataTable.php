<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\ProductFacade;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Livewire\Component;

class ProductDataTable extends LivewireDatatable
{
    public $model = Product::class;

    protected $listeners = ['deleteRecord', 'publishProduct', 'unpublishProduct'];

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
            Column::name('points')->label('Points'),
            Column::name('level')->label('Level'),
            Column::raw("DATE_FORMAT(products.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.products.actions', ['id' => $id, 'status' => $status]);
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
        if ($type == Product::PAYMENT_TYPE['ONE_TIME']) {
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
        if ($status == Product::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == Product::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }


    /**
     * publishProduct
     *
     * @param  mixed $productId
     * @return void
     */
    function publishProduct(int $productId)
    {
        $product = ProductFacade::get($productId);

        if ($product->image && $product->terms) {
            $response = ProductFacade::update($product, array('status' => Product::STATUS['PUBLISHED']));


            if ($response) {
                Session::flash('alert-success', 'Product unpublished successfully');
                return redirect()->route('products.all')->with($response);
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('products.all')->with($response);
            }
        }elseif(!$product->terms){
            Session::flash('alert-warning', 'Update Product Terms Before Publish!');
            return redirect()->route('products.all');
        } else {
            Session::flash('alert-warning', 'Update Image Before Publish!');
            return redirect()->route('products.all');
        }
    }


    /**
     * unpublishProduct
     *
     * @param  mixed $productId
     * @return void
     */
    function unpublishProduct(int $productId)
    {

        $product = ProductFacade::get($productId);
        $response = ProductFacade::update($product, array('status' => Product::STATUS['DRAFT']));


        if ($response) {
            Session::flash('alert-success', 'Product unpublished successfully');
            return redirect()->route('products.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('products.all')->with($response);
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
        $product= ProductFacade::get($productId);
        $response = ProductFacade::delete($product);


        if ($response ) {
            Session::flash('alert-success', 'Product deleted successfully');
            return redirect()->route('products.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('products.all')->with($response);
        }
    }
}
