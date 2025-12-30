<?php

namespace App\Http\Livewire\Milestone;

use domain\Facades\MilestoneFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class MilestoneCreateForm extends Component
{
    public $name, $level, $count, $type;

    public function render()
    {

        return view('pages.milestones.components.create-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'level' => 'required|numeric|min:1',
            'count' => 'required|numeric|min:1',
            'type' => 'required',

        ];
    }
    protected $messages = [
        'name.required' => 'Please Enter Name',
        'level.required' => 'Please Enter Level',
        'count.required' => 'Please Enter Count',
        'type.required' => 'Please Select Type',

    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();
        $milestone = MilestoneFacade::create($validatedData);

        if ($milestone) {
            Session::flash('alert-success', 'Milestone Created Successfully');
            return redirect()->route('milestones.all');
        }
    }


    /**
     * resetForm
     *
     * @return void
     */
    public function resetForm()
    {
        $this->reset(['name', 'level', 'count', 'type']);
    }
}
