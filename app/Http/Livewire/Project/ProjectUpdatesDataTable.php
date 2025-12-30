<?php

namespace App\Http\Livewire\Project;


use App\Models\Project;
use App\Models\ProjectUpdate;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\ProjectUpdateFacade;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Livewire\Component;

class ProjectUpdatesDataTable extends LivewireDatatable
{
    public $projectId;
    public $model = Project::class;

    protected $listeners = ['deleteRecord', 'publishProject', 'unpublishProject'];

      /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return ProjectUpdate::query()
            ->where('project_id', $this->projectId);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::name('title')->label('Title'),
            Column::name('description')->label('Description'),

            Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),

            Column::raw("DATE_FORMAT(project_updates.deliver_date, '%Y/%m/%d') AS Created At")->label('Delivery Date'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.projects.components.update-actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }



     /**
     * status
     *
     * @param  mixed $status
     * @return string
     */
    public function status($status): string
    {
        $data = '<div class="text-center">';
        if ($status == ProjectUpdate::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == ProjectUpdate::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }


    /**
     * publishProject
     *
     * @param  mixed $projectId
     * @return void
     */
    function publishProject(int $projectUpdateId)
    {
        $project = ProjectUpdateFacade::get($projectUpdateId);

        if ($project->projectUpdateImages) {
            $response = ProjectUpdateFacade::update($project, array('status' => ProjectUpdate::STATUS['PUBLISHED']));

            if ($response) {
                Session::flash('alert-success', 'Project update unpublished successfully');
                return redirect()->route('projects.updates',['id' => $this->projectId])->with($response);
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('projects.updates')->with($response);
            }
        } else {
            Session::flash('alert-warning', 'Update Images Before Publish!');
            return redirect()->route('projects.updates',['id' => $this->projectId]);
        }
    }


    /**
     * unpublishProject
     *
     * @param  mixed $projectUpdateId
     * @return void
     */
    function unpublishProject(int $projectUpdateId)
    {

        $project = ProjectUpdateFacade::get($projectUpdateId);
        $response = ProjectUpdateFacade::update($project, array('status' => ProjectUpdate::STATUS['DRAFT']));

        if ($response) {
            Session::flash('alert-success', 'Project update unpublished successfully');
            return redirect()->route('projects.updates',['id' => $this->projectId])->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('projects.updates',['id' => $this->projectId])->with($response);
        }
    }


     /**
     * deleteRecord
     *
     * @param  mixed $projectUpdateId
     * @return void
     */
    function deleteRecord(int $projectUpdateId)
    {
        $project= ProjectUpdateFacade::get($projectUpdateId);
        $response = ProjectUpdateFacade::delete($project);


        if ($response ) {
            Session::flash('alert-success', 'Project update deleted successfully');
            return redirect()->route('projects.updates',['id' => $this->projectId])->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('projects.updates',['id' => $this->projectId])->with($response);
        }
    }
}
