<?php

namespace domain\Services;

use App\Models\CustomerPurchase;
use Illuminate\Database\Eloquent\Collection;


class CustomerPurchaseService
{

    protected $customerPurchase;

    public function __construct()
    {
        $this->customerPurchase = new CustomerPurchase();
    }
    /**
     * Get customerPurchase using id
     *
     * @param  int $id
     *
     * @return CustomerPurchase
     */
    public function get(int $id): CustomerPurchase
    {
        return $this->customerPurchase->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customerPurchase->all();
    }
    /**
     * create
     *
     * @param  mixed $customerPurchase
     * @return CustomerPurchase
     */
    public function create(array $customerPurchase): CustomerPurchase
    {
        return $this->customerPurchase->create($customerPurchase);
    }
    /**
     * Update customerPurchase
     *
     * @param CustomerPurchase $customerPurchase
     * @param array $data
     *
     * @return void
     */
    public function update(CustomerPurchase $customerPurchase, array $data): void
    {
        $customerPurchase->update($this->edit($customerPurchase, $data));
    }
    /**
     * Edit customerPurchase
     *
     * @param CustomerPurchase $customerPurchase
     * @param array $data
     *
     * @return array
     */
    protected function edit(CustomerPurchase $customerPurchase, array $data): array
    {
        return array_merge($customerPurchase->toArray(), $data);
    }
    /**
     * Delete a customerPurchase
     *
     * @param CustomerPurchase $customerPurchase
     *
     * @return void
     */
    public function delete(CustomerPurchase $customerPurchase): void
    {
        $customerPurchase->delete();
    }


    /**
     * getPurchaseTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchaseTotalByDate($date){
        return $this->customerPurchase->getPurchaseTotalByDate($date);
    }

    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date){
        return $this->customerPurchase->getPurchasedPointsTotalByDate($date);
    }

    /**
     * getPurchasedTotalByCustomers
     *
     * @param  mixed $customers
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedTotalByCustomers($customers,$date){
        return $this->customerPurchase->getPurchasedTotalByCustomers($customers,$date);
    }

    /**
     * getQuotaAvailableByCustomer
     *
     * @param  mixed $customers_id
     * @return void
     */
    public function getQuotaAvailableByCustomer($customers_id)
    {
        return $this->customerPurchase->getQuotaAvailableByCustomer($customers_id);
    }

    /**
     * getQuotaAvailableByCustomer
     *
     * @param  mixed $customers_id
     * @return void
     */
    public function getTotalAvailableQuotaByCustomer($customers_id)
    {
        return $this->customerPurchase->getTotalAvailableQuotaByCustomer($customers_id);
    }

    /**
     * getActiveCustomers
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getProductPurchasedCustomersByIds($customerIds,$date)
    {
        return $this->customerPurchase->getProductPurchasedCustomersByIds($customerIds,$date);
    }

    /**
     * getProductPurchasedCustomersWithPeriod
     *
     * @param  mixed $date
     * @return void
     */
    public function getProductPurchasedCustomersWithPeriod($date)
    {
        return $this->customerPurchase->getProductPurchasedCustomersWithPeriod($date);
    }

     /**
     * getPurchasedReferralsByDirectReferral
     *
     * @param  mixed $directReferral
     * @return void
     */
    public function getPurchasedReferralsByDirectReferral($directReferral)
    {
        return $this->customerPurchase->getPurchasedReferralsByDirectReferral($directReferral);
    }

       /**
     * getPurchasedTotalByCustomerId
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function getPurchasedTotalByCustomerId($customer_id)
    {
        return $this->customerPurchase->getPurchasedTotalByCustomerId($customer_id);
    }

}
