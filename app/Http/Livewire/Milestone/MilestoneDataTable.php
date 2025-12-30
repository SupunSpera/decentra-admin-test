<?php

namespace App\Http\Livewire\Milestone;

use App\Models\Milestone;
use App\Models\Product;
use domain\Facades\MilestoneFacade;
use Illuminate\Support\Facades\Session;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Livewire\Component;

class MilestoneDataTable extends LivewireDatatable
{
    public $model = Milestone::class;

    protected $listeners = ['deleteRecord', 'publishProduct', 'unpublishProduct'];

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
            Column::callback(['type'], function ($payment_type) {
                return $this->type($payment_type);
            })->label('Type'),
            Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),
            Column::name('count')->label('Count'),
            Column::name('level')->label('Level'),
            Column::raw("DATE_FORMAT(milestones.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.milestones.actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }

    /**
     * type
     *
     * @param  mixed $type
     * @return string
     */
    public function type($type): string
    {
        $data = '<div class="text-center">';
        if ($type == Milestone::TYPE['DIRECT_REFERRAL']) {
            $data = $data . '<span class="badge badge-success">Direct Referral</span>';
        } elseif($type == Milestone::TYPE['CLIENT_BASE']) {
            $data = $data . '<span class="badge badge-info">Client Base</span>';
        }
        return $data . '</div>';
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
        if ($status == Product::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == Product::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }

     /**
     * deleteRecord
     *
     * @param  mixed $milestoneId
     * @return void
     */
    function deleteRecord(int $id)
    {
        $milestone= MilestoneFacade::get($id);
        $response = MilestoneFacade::delete($milestone);


        if ($response ) {
            Session::flash('alert-success', 'Milestone deleted successfully');
            return redirect()->route('milestones.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('milestones.all')->with($response);
        }
    }
}
