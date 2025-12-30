<?php

namespace App\Http\Livewire\Customer;


use App\Models\Referral;
use domain\Facades\ReferralFacade;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

/**
 * Customer Referral Data Table
 *
 * php version 8
 *
 * @category Livewire
 * @author   Spera Labs
 * @license  https://decentrax.com Config
 * @link     https://decentrax.com/
 *
 * */
class CustomerReferralDataTable extends LivewireDatatable
{
    public $model = Referral::class;
    public $customer_id, $referral;

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {

        return Referral::query()
            ->where('direct_referral_id', $this->customer_id);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return $this->getReferralDetails($id);
            })->label('Email'),
            Column::name('level')->label('Tree Level'),
        ];
    }


     /**
     * getReferralDetaoils
     *
     * @param  mixed $referralId
     * @return
     */
    public function getReferralDetails($id)
    {
        $data = '<div class="text-center">';
        $referral = ReferralFacade::get($id);
        $data = $data . '<span class="badge badge-info">'.$referral->customer->email.'</span>';

        return $data . '</div>';
    }

}
