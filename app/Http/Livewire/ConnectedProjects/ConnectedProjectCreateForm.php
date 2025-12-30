<?php

namespace App\Http\Livewire\ConnectedProjects;

use domain\Facades\ConnectedProjectFacade;
use domain\Facades\ProjectFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ConnectedProjectCreateForm extends Component
{
    public $name, $public_url, $admin_url;

    public function render()
    {

        return view('pages.connected_projects.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'public_url' => 'required|url|unique:connected_projects,public_url',
            'admin_url' => 'required|url|unique:connected_projects,admin_url'

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

        $project = ConnectedProjectFacade::create($validatedData);

        if ($project) {
            Session::flash('alert-success', 'Project created successfully');
            return redirect()->route('connected-projects.all');
        }
    }
}
