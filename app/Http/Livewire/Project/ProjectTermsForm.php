<?php

namespace App\Http\Livewire\Project;

use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Session;
use domain\Facades\ProjectFacade;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProjectTermsForm extends Component
{
    use WithFileUploads;
    public $project, $projectId, $terms;
    protected $listeners = ['deleteImage'];

    public function mount()
    {

        $this->project = ProjectFacade::get($this->projectId);

        $this->terms = $this->project->terms;

        // if ($this->project['image']) {
        //     $this->projectImage = $this->project['image']['name'];
        // }

    }

    public function render()
    {
        return view('pages.projects.components.terms-update-form');
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
            return redirect()->route('projects.terms', $this->projectId);
        }
        if ($this->project->image_id) {
            $validatedData['status'] = Project::STATUS['PUBLISHED'];
        }
        $project = ProjectFacade::update($this->project, $validatedData);

        if ($project) {
            if (!$this->project->image_id) {
                Session::flash('alert-success', 'Project terms updated successfully');
                return redirect()->route('projects.edit', $this->project->id);
            } else {
                Session::flash('alert-success', 'Project terms updated successfully');
                return redirect()->route('projects.all');
            }
        }
    }


}
