<?php

namespace App\Http\Livewire\Project;


use App\Models\Project;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\ProjectFacade;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Livewire\Component;

class ProjectDataTable extends LivewireDatatable
{
    public $model = Project::class;

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
            // Column::name('description')->label('Description'),

            Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),
            Column::name('total_value')->label('Total Value'),
            Column::raw("DATE_FORMAT(projects.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.projects.actions', ['id' => $id, 'status' => $status]);
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
        if ($status == Project::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == Project::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }else if ($status == Project::STATUS['STARTED']) {
            $data = $data . '<span class="badge badge-primary">Started</span>';
        }else if ($status == Project::STATUS['COMPLETED']) {
            $data = $data . '<span class="badge badge-dark">Completed</span>';
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
        $product = ProjectFacade::get($productId);

        if ($product->image && $product->terms) {
            $response = ProjectFacade::update($product, array('status' => Project::STATUS['PUBLISHED']));


            if ($response) {
                Session::flash('alert-success', 'Project unpublished successfully');
                return redirect()->route('projects.all')->with($response);
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('projects.all')->with($response);
            }
        }elseif(!$product->terms){
            Session::flash('alert-warning', 'Update Project Terms Before Publish!');
            return redirect()->route('projects.all');
        } else {
            Session::flash('alert-warning', 'Update Image Before Publish!');
            return redirect()->route('projects.all');
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

        $product = ProjectFacade::get($productId);
        $response = ProjectFacade::update($product, array('status' => Project::STATUS['DRAFT']));


        if ($response) {
            Session::flash('alert-success', 'Project unpublished successfully');
            return redirect()->route('projects.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('projects.all')->with($response);
        }
    }


     /**
     * deleteRecord
     *
     * @param  mixed $productId
     * @return void
     */
    function deleteRecord(int $productId)
    {
        $product= ProjectFacade::get($productId);
        $response = ProjectFacade::delete($product);


        if ($response ) {
            Session::flash('alert-success', 'Project deleted successfully');
            return redirect()->route('projects.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('projects.all')->with($response);
        }
    }
}
