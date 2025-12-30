<?php

namespace App\Http\Livewire\ConnectedProjects;

use domain\Facades\ConnectedProjectFacade;
use domain\Facades\ProjectFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ConnectedProjectUpdateForm extends Component
{
    public $name, $public_url, $admin_url, $projectId, $project;

    public function mount()
    {
        $this->project = ConnectedProjectFacade::get($this->projectId);
        $this->name = $this->project->name;
        $this->public_url = $this->project->public_url;
        $this->admin_url = $this->project->admin_url;
    }

    public function render()
    {

        return view('pages.connected_projects.components.update-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'public_url' => 'required|url|unique:connected_projects,public_url,' . $this->projectId,
            'admin_url' => 'required|url|unique:connected_projects,admin_url,' . $this->projectId,
        ];
    }


    protected $messages = [
        'name.required' => 'Please Enter Name',
        'public_url.required' => 'Please Enter Public URL',
        'public_url.url' => 'Please Enter Valid URL',
        'admin_url.required' => 'Please Enter Admin URL',
        'admin_url.url' => 'Please Enter Valid URL',
    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        $project = ConnectedProjectFacade::update($this->project, $validatedData);

        if ($project) {
            Session::flash('alert-success', 'Project updated successfully');
            return redirect()->route('connected-projects.all');
        }
    }
}
