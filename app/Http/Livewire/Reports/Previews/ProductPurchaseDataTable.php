<?php

namespace App\Http\Livewire\Reports\Previews;

use App\Models\DailyTotalShare;
use App\Models\ProductPurchase;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProductPurchaseDataTable extends LivewireDatatable
{
    public $model = DailyTotalShare::class;
    public $startDate,$endDate;


    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        $query = ProductPurchase::query()
            ->join('products', 'product_purchases.product_id', '=', 'products.id')
            ->join('customers', 'product_purchases.customer_id', '=', 'customers.id')
            ->select(
                'product_purchases.*',
                'products.name as product_name',
                'products.price as product_price',
                'customers.email as customer_email'
            );

        // Apply filters if dates are provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('product_purchases.created_at', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->whereDate('product_purchases.created_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->whereDate('product_purchases.created_at', '<=', $this->endDate);
        }

        return $query;
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
            Column::callback(['product.name'], function ($name) {
                return $name;
            })->label('Token Name')->searchable(),
            Column::callback(['customer.email'], function ($email) {
                return $email;
            })->label('Customer')->searchable(),
            Column::callback(['product.price'], function ($price) {
                return $price;
            })->label('Token Value')->searchable(),
            Column::raw("DATE_FORMAT(product_purchases.created_at, '%Y/%m/%d') AS Created At")->label(' Bought Date'),
        ];
    }
}
