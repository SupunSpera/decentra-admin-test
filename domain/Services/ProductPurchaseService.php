<?php

namespace domain\Services;

use App\Models\ProductPurchase;
use Illuminate\Database\Eloquent\Collection;


class ProductPurchaseService
{

    protected $productPurchase;

    public function __construct()
    {
        $this->productPurchase = new ProductPurchase();
    }
    /**
     * Get productPurchase using id
     *
     * @param  int $id
     *
     * @return ProductPurchase
     */
    public function get(int $id): ProductPurchase
    {
        return $this->productPurchase->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->productPurchase->all();
    }
    /**
     * create
     *
     * @param  mixed $productPurchase
     * @return ProductPurchase
     */
    public function create(array $productPurchase): ProductPurchase
    {
        return $this->productPurchase->create($productPurchase);
    }
    /**
     * Update productPurchase
     *
     * @param ProductPurchase $productPurchase
     * @param array $data
     *
     * @return void
     */
    public function update(ProductPurchase $productPurchase, array $data): void
    {
        $productPurchase->update($this->edit($productPurchase, $data));
    }
    /**
     * Edit productPurchase
     *
     * @param ProductPurchase $productPurchase
     * @param array $data
     *
     * @return array
     */
    protected function edit(ProductPurchase $productPurchase, array $data): array
    {
        return array_merge($productPurchase->toArray(), $data);
    }
    /**
     * Delete a productPurchase
     *
     * @param ProductPurchase $productPurchase
     *
     * @return void
     */
    public function delete(ProductPurchase $productPurchase): void
    {
        $productPurchase->delete();
    }


    /**
     * getPurchaseTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchaseTotalByDate($date){
        return $this->productPurchase->getPurchaseTotalByDate($date);
    }

    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date){
        return $this->productPurchase->getPurchasedPointsTotalByDate($date);
    }

    /**
     * getPurchasedTotalByCustomers
     *
     * @param  mixed $customers
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedTotalByCustomers($customers,$date){
        return $this->productPurchase->getPurchasedTotalByCustomers($customers,$date);
    }

    /**
     * getQuotaAvailableByCustomer
     *
     * @param  mixed $customers_id
     * @return void
     */
    public function getQuotaAvailableByCustomer($customers_id)
    {
        return $this->productPurchase->getQuotaAvailableByCustomer($customers_id);
    }

    /**
     * getQuotaAvailableByCustomer
     *
     * @param  mixed $customers_id
     * @return void
     */
    public function getTotalAvailableQuotaByCustomer($customers_id)
    {
        return $this->productPurchase->getTotalAvailableQuotaByCustomer($customers_id);
    }

    /**
     * getActiveCustomers
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getProductPurchasedCustomersByIds($customerIds,$date)
    {
        return $this->productPurchase->getProductPurchasedCustomersByIds($customerIds,$date);
    }

    /**
     * getProductPurchasedCustomersWithPeriod
     *
     * @param  mixed $date
     * @return void
     */
    public function getProductPurchasedCustomersWithPeriod($date)
    {
        return $this->productPurchase->getProductPurchasedCustomersWithPeriod($date);
    }

     /**
     * getPurchasedReferralsByDirectReferral
     *
     * @param  mixed $directReferral
     * @return void
     */
    public function getPurchasedReferralsByDirectReferral($directReferral)
    {
        return $this->productPurchase->getPurchasedReferralsByDirectReferral($directReferral);
    }

       /**
     * getPurchasedTotalByCustomerId
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function getPurchasedTotalByCustomerId($customer_id)
    {
        return $this->productPurchase->getPurchasedTotalByCustomerId($customer_id);
    }


}
