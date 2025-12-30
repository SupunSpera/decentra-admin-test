<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use domain\Facades\ProductFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductUpdateForm extends Component
{
    use WithFileUploads;
    public $product, $productId, $name, $description, $payment_type, $price, $points, $level, $image, $productImage;

    public function mount()
    {

        $this->product = ProductFacade::get($this->productId);

        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->payment_type = $this->product->payment_type;
        $this->price = $this->product->price;
        $this->points = $this->product->points;
        $this->level = $this->product->level;

        if ($this->product['image']) {
            $this->productImage = $this->product['image']['name'];
        }

    }

    public function render()
    {
        return view('pages.products.components.update-form');
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'payment_type' => 'required',
            'price' => 'required|numeric|min:1|max:9999999',
            'points' => 'required|numeric|min:0|max:9999999',
            'level' => 'required|numeric|min:0',
            // |unique:products,level,' . $this->productId

        ];

        if ($this->image || $this->productImage == null) {
            $rules['image'] = ['image', 'max:1024'];
        }

        return $rules;
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'payment_type.required' => 'Please Select Payment Type',
        'price.required' => 'Please Enter Price',
        'price.numeric' => 'Price should be a numeric value',
        'points.required' => 'Please Enter Price',
        'points.numeric' => 'Price should be a numeric value',
        'image.image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
        'image.max' => 'The file may not be greater than 1024 kilobytes (1 MB)',

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {

        $validatedData = $this->validate();
        if($this->product->terms){
            $validatedData['status'] = Product::STATUS['PUBLISHED'];
        }

        if ($this->image == null && $this->productImage == null) {
            $this->validate([
                'image' => 'image|max:1024',
            ]);
        }
        if ($this->image) {
            $uploadedImage = ProductFacade::uploadImage($this->image);
            $validatedData['image_id'] = $uploadedImage->id;
        }

        $product = ProductFacade::update($this->product, $validatedData);

        if ($product) {
            if (!$this->product->terms) {
                Session::flash('alert-success', 'Product updated successfully');
                return redirect()->route('products.terms', $this->product->id);
            }else{
                Session::flash('alert-success', 'Product updated successfully');
                return redirect()->route('products.all');
            }
        }
    }
}
