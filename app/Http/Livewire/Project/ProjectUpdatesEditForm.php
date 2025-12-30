<?php

namespace App\Http\Livewire\Project;

use App\Models\ProjectUpdate;
use App\Models\ProjectUpdateImage;
use domain\Facades\ProjectUpdateFacade;
use domain\Facades\ProjectUpdateImageFacade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectUpdatesEditForm extends Component
{
    use WithFileUploads;

    public $projectUpdateId, $projectUpdate, $title, $description, $deliver_date, $images = [], $projectUpdateImages = [];

    protected $listeners = ['deleteImage'];

    public function mount()
    {

        $this->projectUpdate = ProjectUpdateFacade::get($this->projectUpdateId);

        $this->title = $this->projectUpdate->title;
        $this->description = $this->projectUpdate->description;
        $this->deliver_date = $this->projectUpdate->deliver_date;

        if (isset($this->projectUpdate->projectUpdateImages)) {
            if (($this->projectUpdate->projectUpdateImages->count()) > 0) {

                $this->projectUpdateImages = $this->projectUpdate->projectUpdateImages; // Assuming multiple images
            } else {
                $this->projectUpdateImages = [];
            }
        }
    }

    public function render()
    {
        return view('pages.projects.components.updates-edit-form');
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
        if (count($this->projectUpdateImages) === 0) {
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

        $projectUpdate = ProjectUpdateFacade::create($validatedData);

        if($projectUpdate){
            if (count($this->images) > 0) {
                $uploadedImages = [];
                foreach ($this->images as $image) {
                    $uploadedImage = ProjectUpdateFacade::uploadImage($image);
                    ProjectUpdateImageFacade::create([
                        'project_id' =>  $this->projectUpdate->project_id,
                        'image_id' => $uploadedImage->id,
                        'project_update_id' => $this->projectUpdate->id,
                    ]);
                    $uploadedImages[] = $uploadedImage->id;
                }

            }

            $projectUpdate = ProjectUpdateFacade::update($this->projectUpdate, $validatedData);

            if ($projectUpdate) {
                Session::flash('alert-success', 'Project update updated successfully');
            return redirect()->route('projects.updates',['id' => $this->projectUpdate->project_id]);
            }


        }

    }

      /**
     * deleteImage
     *
     * @param  mixed $imageId
     * @return void
     */
    public function deleteImage($imageId)
    {
        $projectImage = ProjectUpdateImage::find($imageId);

        if ($projectImage) {
            // Delete the image file from storage
            Storage::delete('/uploads/images/projects/updates/' . $projectImage->image->name);

            // Delete the image record from the database
            $projectImage->delete();

            // Refresh the component state by reloading the images
            $this->projectUpdateImages = ProjectUpdateImage::where('project_update_id', $this->projectUpdate->id)->get();
            Session::flash('alert-success', 'Image deleted successfully');
        } else {
            session()->flash('error', 'Image not found.');
        }
    }
}
