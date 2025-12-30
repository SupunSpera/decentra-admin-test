<?php

namespace domain\Services;

use App\Models\ReducedCustomerSupportingBonus;
use Illuminate\Database\Eloquent\Collection;

class ReducedCustomerSupportingBonusService
{

    protected $reducedCustomerSupporting;

    public function __construct()
    {
        $this->reducedCustomerSupporting = new ReducedCustomerSupportingBonus();
    }
    /**
     * Get reducedCustomerSupporting using id
     *
     * @param  int $id
     *
     * @return ReducedCustomerSupportingBonus
     */
    public function get(int $id): ReducedCustomerSupportingBonus
    {
        return $this->reducedCustomerSupporting->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->reducedCustomerSupporting->all();
    }
    /**
     * create
     *
     * @param  mixed $reducedCustomerSupporting
     * @return ReducedCustomerSupportingBonus
     */
    public function create(array $reducedCustomerSupporting): ReducedCustomerSupportingBonus
    {
        return $this->reducedCustomerSupporting->create($reducedCustomerSupporting);
    }
    /**
     * Update reducedCustomerSupporting
     *
     * @param ReducedCustomerSupportingBonus $reducedCustomerSupporting
     * @param array $data
     *
     *
     */
    public function update(ReducedCustomerSupportingBonus $reducedCustomerSupporting, array $data)
    {
        return  $reducedCustomerSupporting->update($this->edit($reducedCustomerSupporting, $data));
    }
    /**
     * Edit reducedCustomerSupporting
     *
     * @param ReducedCustomerSupportingBonus $reducedCustomerSupporting
     * @param array $data
     *
     * @return array
     */
    protected function edit(ReducedCustomerSupportingBonus $reducedCustomerSupporting, array $data): array
    {
        return array_merge($reducedCustomerSupporting->toArray(), $data);
    }
    /**
     * Delete a reducedCustomerSupporting
     *
     * @param ReducedCustomerSupportingBonus $reducedCustomerSupporting
     *
     *
     */
    public function delete(ReducedCustomerSupportingBonus $reducedCustomerSupporting)
    {
        return $reducedCustomerSupporting->delete();
    }



    /**
     * getAvailableReducedSupportingBonusByCustomerAndSide
     *
     * @param  mixed $customer_id
     * @param  mixed $side
     * @return void
     */
    public function getAvailableReducedSupportingBonusByCustomerAndSide($customer_id,$side)
    {
        return $this->reducedCustomerSupporting->getAvailableReducedSupportingBonusByCustomerAndSide($customer_id,$side);
    }

}
