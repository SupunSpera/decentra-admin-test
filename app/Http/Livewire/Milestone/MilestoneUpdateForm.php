<?php

namespace App\Http\Livewire\Milestone;

use App\Models\Milestone;
use domain\Facades\MilestoneFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class MilestoneUpdateForm extends Component
{
    use WithFileUploads;
    public $milestone, $milestoneId, $name, $type, $count,  $level, $image,$milestoneImage;

    public function mount()
    {

        $this->milestone = MilestoneFacade::get($this->milestoneId);

        $this->name = $this->milestone->name;
        $this->type = $this->milestone->type;
        $this->count = $this->milestone->count;
        $this->level = $this->milestone->level;

        if ($this->milestone['image']) {
            $this->milestoneImage = $this->milestone['image']['name'];
        }

    }

    public function render()
    {
        return view('pages.milestones.components.update-form');
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'level' => 'required|numeric|min:1',
            'count' => 'required|numeric|min:1',
            'type' => 'required',

        ];

        if ($this->image || $this->milestoneImage == null) {
            $rules['image'] = ['image', 'max:1024'];
        }

        return $rules;
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'count.required' => 'Please Enter Count',
        'level.required' => 'Please Select Payment Type',
        'type.required' => 'Please Select Type',
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
        $validatedData['status'] = Milestone::STATUS['PUBLISHED'];

        if ($this->image == null && $this->milestoneImage == null) {
            $this->validate([
                'image' => 'image|max:1024',
            ]);
        }
        if ($this->image) {
            $uploadedImage = MilestoneFacade::uploadImage($this->image);
            $validatedData['image_id'] = $uploadedImage->id;
        }

        $milestone = MilestoneFacade::update($this->milestone, $validatedData);

        if ($milestone) {
            Session::flash('alert-success', 'Milestone updated successfully');
            return redirect()->route('milestones.all');
        }
    }
}
