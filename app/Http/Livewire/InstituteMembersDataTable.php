<?php

namespace App\Http\Livewire;

use App\Models\InstituteMember;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InstituteMembersDataTable extends LivewireDatatable
{

    public $institute;
    public $model = InstituteMember::class;

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return InstituteMember::query()
        ->leftJoin('customers', 'customers.id', 'institute_members.customer_id')
        ->where('institute_members.institute_id', '=', $this->institute->id);
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

            Column::name('institute_members.email')->label('Email'),
            Column::callback(['institute_members.type'], function ($type) {
                return $this->getRole($type);
            })->label('Role'),
            Column::callback(['institute_members.status'], function ($status) {
                return $this->getStatus($status);
            })->label('Status')
        ];
    }

    /**
     * getRole
     *
     * @param  mixed $type
     * @return string
     */
    public function getRole($role): string
    {
        $data = '<div class="text-center">';
        if ($role == InstituteMember::TYPE['PRESIDENT']) {
            $data = $data . '<span class="badge badge-success">President</span>';
        } else if ($role == InstituteMember::TYPE['MEMBER']) {
            $data = $data . '<span class="badge badge-warning">Member</span>';
        }
        return $data . '</div>';
    }
     /**
     * getStatus
     *
     * @param  mixed $status
     * @return string
     */
    public function getStatus($status): string
    {
        $data = '<div class="text-center">';
        if ($status == InstituteMember::STATUS['ACTIVE']) {
            $data = $data . '<span class="badge badge-success">Active</span>';
        } else if ($status == InstituteMember::STATUS['PENDING']) {
            $data = $data . '<span class="badge badge-warning">Pending</span>';
        } else if ($status == InstituteMember::STATUS['DISABLED']) {
            $data = $data . '<span class="badge badge-dark">Disabled</span>';
        }
        return $data . '</div>';
    }


}
