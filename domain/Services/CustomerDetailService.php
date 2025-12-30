<?php

namespace domain\Services;

use App\Models\CustomerDetails;
use Illuminate\Database\Eloquent\Collection;

class CustomerDetailService
{


    protected $customerDetails;

    public function __construct()
    {
        $this->customerDetails = new CustomerDetails();
    }

    /**
     * Get customerDetails using id
     *
     * @param  int $id
     *
     * @return CustomerDetails
     */
    public function get(int $id): CustomerDetails
    {
        return $this->customerDetails->find($id);
    }

    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customerDetails->all();
    }

    /**
     * create
     *
     * @param  mixed $customerDetails
     * @return CustomerDetails
     */
    public function create(array $customerDetails): CustomerDetails
    {
        return $this->customerDetails->create($customerDetails);
    }

    /**
     * update
     *
     * @param  mixed $customerDetails
     * @param  mixed $data
     *
     */
    public function update(CustomerDetails $customerDetails, array $data)
    {
        return $customerDetails->update($this->edit($customerDetails, $data));
    }

    /**
     * Edit customerDetails
     *
     * @param CustomerDetails $customerDetails
     * @param array $data
     *
     * @return array
     */
    protected function edit(CustomerDetails $customerDetails, array $data): array
    {
        return array_merge($customerDetails->toArray(), $data);
    }

    /**
     * Delete a customerDetails
     *
     * @param CustomerDetails $customerDetails
     *
     * @return void
     */
    public function delete(CustomerDetails $customerDetails): void
    {
        $customerDetails->delete();
    }

    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return CustomerDetails
     */
    public function getByCustomerId($id): ?CustomerDetails
    {
        return $this->customerDetails->getByCustomerId($id);
    }
}
