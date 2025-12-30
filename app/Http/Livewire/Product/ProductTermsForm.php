<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use domain\Facades\ProductFacade;
use Livewire\Component;

class ProductTermsForm extends Component
{
    public $product, $productId, $terms;
    protected $listeners = ['deleteImage'];

    public function mount()
    {

        $this->product = ProductFacade::get($this->productId);

        $this->terms = $this->product->terms;
    }

    public function render()
    {
        return view('pages.products.components.terms-update-form');
    }

    protected function rules()
    {
        $rules = [
            'terms' => 'required|string',

        ];


        return $rules;
    }
    protected $messages = [
        'terms.required' => 'Please Enter Terms & Conditions',

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {

        $validatedData = $this->validate();
        if (empty(trim($this->terms)) || $this->terms === '<p><br></p>') {
            session()->flash('alert-danger', 'Terms & Conditions cannot be empty.');
            return redirect()->route('products.terms', $this->productId);
        }

        if ($this->product->image_id) {
            $validatedData['status'] = Product::STATUS['PUBLISHED'];
        }
        $product = ProductFacade::update($this->product, $validatedData);

        if ($product) {
            if (!$this->product->image_id) {
                Session::flash('alert-success', 'Product terms updated successfully');
                return redirect()->route('products.edit', $this->product->id);
            } else {
                Session::flash('alert-success', 'Product terms updated successfully');
                return redirect()->route('products.all');
            }
        }
    }


}
