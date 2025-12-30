<?php

namespace App\Http\Livewire\Customer;

use domain\Facades\CustomerFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CustomerUpdateForm extends Component
{
    public $customerId,$first_name, $last_name, $email,$referral_id;
    public $referrals,$customer,$referral;

    public function mount()
    {

        $this->customer = CustomerFacade::get($this->customerId);
        $this->referral = ReferralFacade::getByCustomerId($this->customer->id);
        $this->referrals = ReferralFacade::getReferralsExceptCurrent($this->referral->id);

        // $directReferral =  ReferralFacade::getByCustomerId($this->referral->direct_referral_id);






        $this->first_name = $this->customer->first_name;
        $this->last_name = $this->customer->last_name;
        $this->email = $this->customer->email;
        $this->referral_id = $this->referral->direct_referral_id;
    }

    public function render()
    {
        return view('pages.customers.components.update-form');
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:120',
            'referral_id' => 'required',
            // 'password' => ['required', 'min:8', 'same:confirmPassword'],
        ];
    }
    protected $messages = [
        'first_name.required' => 'Please Enter First Name',
        'last_name.required' => 'Please Enter Last Name',
        'email.required' => 'Please Enter Email Address',
        'referral_id.required' => 'Please Select Referral',
        // 'password.required' => 'Please Enter Password',
    ];

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {


        $validatedData = $this->validate();


        $data = array(
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email']
        );

        $customer = CustomerFacade::update($this->customer,$data);

        if ($customer ) {
            Session::flash('alert-success', 'Customer updated successfully');

            return redirect()->route('customers.all');
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('customers.all');
        }
    }




}
