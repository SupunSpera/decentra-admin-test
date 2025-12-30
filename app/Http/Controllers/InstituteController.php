<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use domain\Facades\CustomerFacade;
use domain\Facades\InstituteDetailFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;

class InstituteController extends Controller
{

    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        $activeInstitutes = CustomerFacade::getByTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['ACTIVE']);
        $pendingInstitutes = CustomerFacade::getByTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['PENDING']);

        return view('pages.institutes.all', compact('activeInstitutes','pendingInstitutes'));
    }

    /**
     * all
     *
     * @return void
     */
    public function members()
    {
        $activeInstitutes = CustomerFacade::getByTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['ACTIVE']);
        $pendingInstitutes = CustomerFacade::getByTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['PENDING']);

        return view('pages.institutes.members', compact('activeInstitutes','pendingInstitutes'));
    }

    /**
     * all
     *
     * @return void
     */
    public function refMembers($id)
    {
        // $activeInstitutes = CustomerFacade::getByCustomerTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['ACTIVE']);
        // $pendingInstitutes = CustomerFacade::getByCustomerTypeAndStatus(Customer::TYPE['INSTITUTE'], Customer::STATUS['PENDING']);
        $institute = CustomerFacade::get($id);
        return view('pages.institutes.members', compact('institute'));
    }

     /**
     * view
     *
     * @return void
     */
    public function view($id)
    {


        $institute = CustomerFacade::get($id);
        $referral = ReferralFacade::getByCustomerId($institute->id);
        $instituteDetail = InstituteDetailFacade::getByCustomerId($id);

        if($referral){
            $directReferral =  ReferralFacade::getByCustomerId($referral->direct_referral_id);
            $directReferralCustomer = CustomerFacade::get($directReferral->customer_id);
        }else{
            $directReferralCustomer = null;
        }

        return view('pages.institutes.view', compact('institute', 'directReferralCustomer','instituteDetail'));
    }

      /**
     * wallet
     *
     * @param  mixed $id
     * @return void
     */
    public function wallet($id)
    {
        $institute = CustomerFacade::get($id);
        $wallet = WalletFacade::getByCustomerId($id);

        return view('pages.institutes.wallet', compact('id','wallet','institute'));
    }
}
