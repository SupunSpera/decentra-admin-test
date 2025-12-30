<?php

namespace App\Http\Livewire\Gift;

use App\Models\Gift;
use domain\Facades\GiftFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class GiftUpdateForm extends Component
{
    use WithFileUploads;
    public $gift,$giftId,$name, $description,$token_amount,$image,$giftImage;

    public function mount()
    {

        $this->gift = GiftFacade::get($this->giftId);


        $this->name = $this->gift->name;
        $this->description = $this->gift->description;
        $this->token_amount = $this->gift->token_amount;

        if ($this->gift['image']) {
            $this->giftImage = $this->gift['image']['name'];
        }

    }

    public function render()
    {
        return view('pages.gifts.components.update-form');
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'token_amount' => 'required|numeric|min:0|max:9999999',

        ];

        if ($this->image || $this->giftImage == null) {
            $rules['image'] = ['image', 'max:1024'];
        }

        return $rules;
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'token_amount.required' => 'Please Enter Price',
        'token_amount.numeric' => 'Price should be a numeric value',
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
        $validatedData['status']=Gift::STATUS['PUBLISHED'];

        if ($this->image == null && $this->giftImage == null) {
            $this->validate([
                'image' => 'image|max:1024',
            ]);
        }
        if ($this->image) {
           $uploadedImage=  GiftFacade::uploadImage($this->image);
           $validatedData['image_id'] = $uploadedImage->id;
        }

        $gift = GiftFacade::update($this->gift,$validatedData);

        if($gift){
            Session::flash('alert-success', 'Gift updated successfully');
            return redirect()->route('gifts.all');
        }
    }
}
