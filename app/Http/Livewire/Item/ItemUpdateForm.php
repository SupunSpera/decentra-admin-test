<?php

namespace App\Http\Livewire\Item;

use App\Models\ConnectedProject;
use App\Models\Item;
use domain\Facades\ItemFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemUpdateForm extends Component
{
    use WithFileUploads;
    public $item, $itemId, $name, $description, $payment_type, $price, $points, $level, $image, $itemImage, $connected_project_id;

    public function mount()
    {
        $this->item = ItemFacade::get($this->itemId);

        $this->name = $this->item->name;
        $this->description = $this->item->description;
        $this->payment_type = $this->item->payment_type;
        $this->price = $this->item->price;
        $this->points = $this->item->points;
        $this->level = $this->item->level;
        $this->connected_project_id = $this->item->connected_project_id;

        if ($this->item['image']) {
            $this->itemImage = $this->item['image']['name'];
        }

    }

    public function render()
    {
        return view('pages.Items.components.update-form', ['connectedProjects' => ConnectedProject::all(),
        ]);
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'payment_type' => 'required',
            'price' => 'required|numeric|min:1|max:9999999',
            'points' => 'required|numeric|min:1|max:9999999',
            'level' => 'required|numeric|min:1|unique:items,level,' . $this->itemId,
            'connected_project_id' => 'nullable|exists:connected_projects,id',

        ];

        if ($this->image || $this->itemImage == null) {
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
        if (empty($this->connected_project_id)) {
            $validatedData['connected_project_id'] = null;
        }

        $validatedData['status'] = Item::STATUS['PUBLISHED'];

        if ($this->image == null && $this->itemImage == null) {
            $this->validate([
                'image' => 'image|max:1024',
            ]);
        }
        if ($this->image) {
            $uploadedImage = ItemFacade::uploadImageImages($this->image);
            $validatedData['image_id'] = $uploadedImage->id;
        }

        $item = ItemFacade::update($this->item, $validatedData);

        if ($item) {
            Session::flash('alert-success', 'Item updated successfully');
            return redirect()->route('items.all');
        }
    }
}
