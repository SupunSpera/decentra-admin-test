<?php

namespace App\Http\Livewire\ConnectedProjects;

use App\Models\ConnectedProject;
use App\Models\Project;
use Carbon\Carbon;
use domain\Facades\ConnectedProjectFacade;
use domain\Facades\CustomerFacade;
use domain\Facades\ProjectFacade;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Livewire\Component;

class ConnectedProjectsDataTable extends LivewireDatatable
{
    public $model = ConnectedProject::class;

    protected $listeners = ['deleteRecord', 'publishProject', 'unpublishProject'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::name('name')->label('Name'),
            Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),
            Column::raw("DATE_FORMAT(connected_projects.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.connected_projects.actions', ['id' => $id, 'status' => $status]);
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
        if ($status == ConnectedProject::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == ConnectedProject::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }


    /**
     * publishProject
     *
     * @param  mixed $productId
     * @return void
     */
    function publishProject(int $productId)
    {
        $product = ConnectedProjectFacade::get($productId);
        $response = ConnectedProjectFacade::update($product, array('status' => Project::STATUS['PUBLISHED']));

        if ($response) {
            Session::flash('alert-success', 'Project unpublished successfully');
            return redirect()->route('connected-projects.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('connected-projects.all')->with($response);
        }
    }


    /**
     * unpublishProject
     *
     * @param  mixed $productId
     * @return void
     */
    function unpublishProject(int $productId)
    {

        $product = ConnectedProjectFacade::get($productId);
        $response = ConnectedProjectFacade::update($product, array('status' => Project::STATUS['DRAFT']));

        if ($response) {
            Session::flash('alert-success', 'Project unpublished successfully');
            return redirect()->route('connected-projects.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('connected-projects.all')->with($response);
        }
    }



}
