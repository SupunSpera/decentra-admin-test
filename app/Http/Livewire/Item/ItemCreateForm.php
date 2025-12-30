<?php

namespace App\Http\Livewire\Item;

use Livewire\Component;
use domain\Facades\ItemFacade;
use Illuminate\Support\Facades\Session;

class ItemCreateForm extends Component
{
    public $name, $description, $payment_type, $price, $level, $points;

    public function render()
    {

        return view('pages.Items.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'payment_type' => 'required',
            'price' => 'required|numeric|min:1|max:9999999',
            'points' => 'required|numeric|min:1|max:9999999',
            'level' => 'required|numeric|min:1|unique:items',

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

        $item = ItemFacade::create($validatedData);

        if ($item) {
            Session::flash('alert-success', 'Item created successfully');
            return redirect()->route('items.all');
        }
    }
}
