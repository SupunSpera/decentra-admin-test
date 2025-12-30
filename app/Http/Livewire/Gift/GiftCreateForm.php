<?php

namespace App\Http\Livewire\Gift;

use domain\Facades\GiftFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class GiftCreateForm extends Component
{
    public $name, $description, $token_amount;

    public function render()
    {

        return view('pages.gifts.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'token_amount' => 'required|numeric|min:0|max:9999999',

        ];
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'token_amount.required' => 'Please Enter Price',
        'token_amount.numeric' => 'Price should be a numeric value'

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        $gift = GiftFacade::create($validatedData);

        if ($gift) {
            Session::flash('alert-success', 'Gift created successfully');
            return redirect()->route('gifts.all');
        }
    }
}
