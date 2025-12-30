<?php

namespace App\Http\Livewire\Project;

use domain\Facades\ProjectFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProjectCreateForm extends Component
{
    public $name, $description, $total_value,$type;

    public function render()
    {

        return view('pages.projects.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'total_value' => 'required|numeric|min:1|max:9999999',
            'type' => 'required',

        ];
    }

    protected $messages = [
        'name.required' => 'Please Enter Name',
        'description.required' => 'Please Enter Description',
        'total_value.required' => 'Please Enter Total Value',
        'total_value.numeric' => 'Total Value should be a numeric value',
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

        $project = ProjectFacade::create($validatedData);

        if ($project) {
            Session::flash('alert-success', 'Project created successfully');
            return redirect()->route('projects.all');
        }
    }
}
