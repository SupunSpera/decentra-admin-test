<?php

namespace domain\Services;

use App\Models\Customer;
use Carbon\Carbon;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{


    protected $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }
    /**
     * Get customer using id
     *
     * @param  int $id
     *
     * @return Customer
     */
    public function get(int $id): Customer
    {
        return $this->customer->find($id);
    }
    /**
     * getByEmail
     *
     * @param  mixed $email
     * @return Customer
     */
    public function getByEmail($email): ?Customer
    {
        return $this->customer->getByEmail($email);
    }
    /**
     * getNfcCardNotActivatedCustomers
     *
     * @param  mixed $activatedCustomerIds
     * @return Collection
     */
    public function  getNfcCardNotActivatedCustomers($activatedCustomerIds): ?Collection
    {
        return $this->customer->getNfcCardNotActivatedCustomers($activatedCustomerIds);
    }
    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customer->all();
    }
    /**
     * create
     *
     * @param  mixed $customer
     * @return Customer
     */
    public function create(array $customer): Customer
    {
        return $this->customer->create($customer);
    }

    /**
     * update
     *
     * @param  mixed $customer
     * @param  mixed $data
     *
     */
    public function update(Customer $customer, array $data)
    {
        return $customer->update($this->edit($customer, $data));
    }
    /**
     * Edit customer
     *
     * @param Customer $customer
     * @param array $data
     *
     * @return array
     */
    protected function edit(Customer $customer, array $data): array
    {
        return array_merge($customer->toArray(), $data);
    }
    /**
     * Delete a customer
     *
     * @param Customer $customer
     *
     * @return void
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }


    /**
     * getByType
     *
     * @param  mixed $type
     * @return void
     */
    public function getByTypeAndStatus($type, $status)
    {
        return $this->customer->getByTypeAndStatus($type, $status);
    }

    /**
     * getByType
     *
     * @param  mixed $type
     * @return void
     */
    public function getByCustomerTypeAndStatus($customerId, $type, $status)
    {
        return $this->customer->getByCustomerTypeAndStatus($customerId, $type, $status);
    }

    /**
     * getByRefCode
     *
     * @param  mixed $code
     * @return void
     */
    public function getByRefCode($code)
    {
        return $this->customer->getByRefCode($code);
    }

    /**
     * getCustomerIdsExceptGiven
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getCustomerIdsExceptGiven($customerIds)
    {
        return $this->customer->getCustomerIdsExceptGiven($customerIds);
    }

    /**
     * inactivateCustomers
     *
     * @return void
     */
    function inactivateCustomers()
    {

        //get date before two months
        $dateBeforeTwoMonths = Carbon::now()->subMonths(2)->format('Y-n-j');

        //get product purchased customers within last two month
        $purchasedCustomers = ProductPurchaseFacade::getProductPurchasedCustomersWithPeriod($dateBeforeTwoMonths)->toArray();

         //get direct referral customers of product purchased customers within last two month
        $purchasedReferrals = ReferralFacade::getReferralIds($purchasedCustomers);
        $purchasedDirectReferrals = ReferralFacade::getDirectReferralIds($purchasedReferrals);
        $directReferralCustomers = ReferralFacade::getCustomerIds($purchasedDirectReferrals)->toArray();

        $activeCustomers = array_unique(array_merge($purchasedCustomers, $directReferralCustomers));

        $inactiveCustomers = $this->customer->getCustomerIdsExceptGiven($activeCustomers);

        //update inactive customers status
        Customer::whereIn('id', $inactiveCustomers)->update(['active_status' => Customer::ACTIVE_STATUS['INACTIVE']]);


    }
}
