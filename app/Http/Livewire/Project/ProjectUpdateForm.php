<?php

namespace App\Http\Livewire\Project;

use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Session;
use domain\Facades\ProjectFacade;
use domain\Facades\ProjectImageFacade;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProjectUpdateForm extends Component
{
    use WithFileUploads;
    public $project, $projectId, $name, $description, $total_value,$minimum_investment,$image, $projectImage, $type,$duration,$duration_type,$harvest,$direct_commission,$points,$harvest_type,$bonus_generation;
    protected $listeners = ['deleteImage'];

    public function mount()
    {

        $this->project = ProjectFacade::get($this->projectId);

        $this->name = $this->project->name;
        $this->description = $this->project->description;

        $this->total_value = $this->project->total_value;
        $this->minimum_investment = $this->project->minimum_investment;
        $this->type = $this->project->type;
        $this->duration = $this->project->duration;
        $this->duration_type = $this->project->duration_type;
        $this->harvest = $this->project->harvest;
        $this->direct_commission = $this->project->direct_commission;
        $this->points = $this->project->points;
        $this->harvest_type = $this->project->harvest_type;
        $this->bonus_generation = $this->project->bonus_generation;

        if ($this->project['image']) {
            $this->projectImage = $this->project['image']['name'];
        }

    }

    public function render()
    {
        return view('pages.projects.components.update-form');
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'total_value' => 'required|numeric|min:1|max:9999999',
            'minimum_investment' => 'required|numeric|min:1|max:9999999',
            'type' => 'required',
            'duration' => 'required|numeric|min:0.1',
            'duration_type' => 'required|numeric',
            'harvest' => 'required|numeric|min:0.1|max:100',
            'direct_commission' => 'required|numeric|min:0.1|max:100',
            'points' => 'required|numeric|min:1',
            'harvest_type' => 'required',
            'bonus_generation' => 'required',
            // 'images.*' => 'image|max:1024',

        ];

        if ($this->image || $this->projectImage == null) {
            $rules['image'] = ['image', 'max:1024'];
        }

        return $rules;
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'total_value.required' => 'Please Enter Price',
        'total_value.numeric' => 'Price should be a numeric value',
        'minimum_investment.required' => 'Please Enter minimum investment',
        'minimum_investment.numeric' => 'Minimum investment should be a numeric value',
        'image.image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
        'image.max' => 'The file may not be greater than 1024 kilobytes (1 MB)',
        'type.required' => 'Please Select Project Type',

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {

        $validatedData = $this->validate();

        if ($this->project->terms) {
            $validatedData['status'] = Project::STATUS['PUBLISHED'];
        }

        if ($this->image == null && $this->projectImage == null) {
            $this->validate([
                'image' => 'image|max:1024',
            ]);
        }
        if ($this->image) {
            $uploadedImage =  ProjectFacade::uploadImage($this->image);
            $validatedData['image_id'] = $uploadedImage->id;
        }

        $project = ProjectFacade::update($this->project, $validatedData);

        if ($project) {
            if (!$this->project->terms) {
                Session::flash('alert-success', 'Project updated successfully');
                return redirect()->route('projects.terms', $this->project->id);
            } else {
                Session::flash('alert-success', 'Project updated successfully');
                return redirect()->route('projects.all');
            }
        }
    }


}
