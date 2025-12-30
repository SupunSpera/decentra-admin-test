<?php

namespace App\Http\Livewire\Project;

use App\Models\ProjectUpdate;
use domain\Facades\ProjectUpdateFacade;
use domain\Facades\ProjectUpdateImageFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectUpdatesCreateForm extends Component
{
    use WithFileUploads;

    public $projectUpdates, $projectId, $title, $description, $deliver_date, $images = [], $projectImages = [];

    public function mount()
    {

        $this->projectUpdates = ProjectUpdateFacade::getByProject($this->projectId);

        if (isset($this->project->projectImages)) {
            if (($this->project->projectImages->count()) > 0) {

                $this->projectImages = $this->project->projectImages; // Assuming multiple images
            } else {
                $this->projectImages = [];
            }
        }
    }

    public function render()
    {
        return view('pages.projects.components.updates-create-form');
    }
    /**
     * updated
     *
     * @param  mixed $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'deliver_date' => 'required',
        ];

        // Require at least one image (existing or new)
        if (count($this->projectImages) === 0) {
            $rules['images'] = 'required'; // At least one image must be uploaded
        }

        $rules['images.*'] = 'image|max:1024'; // Individual image validation

        return $rules;
    }

    protected $messages = [
        'title.required' => 'Please Enter Title',
        'description.required' => 'Please Enter Description',
        'deliver_date.required' => 'Please Enter Delivery Date',
        'images.required' => 'Please upload at least one image.',
        'images.*.image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
        'images.*.max' => 'Each file may not be greater than 1024 kilobytes (1 MB)',
    ];

     /**
     * submit
     *
     * @return void
     */
    public function submit()
    {

        $validatedData = $this->validate();
        $validatedData['status'] = ProjectUpdate::STATUS['PUBLISHED'];
        $validatedData['project_id'] =$this->projectId;

        $projectUpdate = ProjectUpdateFacade::create($validatedData);

        if($projectUpdate){
            if (count($this->images) > 0) {
                $uploadedImages = [];
                foreach ($this->images as $image) {
                    $uploadedImage = ProjectUpdateFacade::uploadImage($image);
                    ProjectUpdateImageFacade::create([
                        'project_id' =>  $this->projectId,
                        'image_id' => $uploadedImage->id,
                        'project_update_id' => $projectUpdate->id,
                    ]);
                    $uploadedImages[] = $uploadedImage->id;
                }

            }

            Session::flash('alert-success', 'Project update added successfully');
            return redirect()->route('projects.updates',['id' => $this->projectId]);
        }



    }
}
