<?php

namespace App\Http\Livewire\Product;

use domain\Facades\ProductFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductCreateForm extends Component
{
    public $name, $description, $payment_type, $price, $level, $points;

    public function render()
    {

        return view('pages.products.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'payment_type' => 'required',
            'price' => 'required|numeric|min:1|max:9999999',
            'points' => 'required|numeric|min:0|max:9999999',
            'level' => [
                'required',
                'numeric',
                'min:0',
                // function ($attribute, $value, $fail) {
                //     // Apply the unique validation only if level is not 0
                //     if ($value != 0) {
                //         if (\App\Models\Product::where('level', $value)->exists()) {
                //             $fail('The level must be unique.');
                //         }
                //     }
                // },
            ],

        ];
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'payment_type.required' => 'Please Select Payment Type',
        'price.required' => 'Please Enter Price',
        'price.numeric' => 'Price should be a numeric value',
        'points.required' => 'Please Enter Point',
        'points.numeric' => 'Point should be a numeric value',

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        $product = ProductFacade::create($validatedData);

        if ($product) {
            Session::flash('alert-success', 'Product created successfully');
            return redirect()->route('products.all');
        }
    }
}
