<?php

namespace domain\Services;

use App\Events\MilestoneCollected;
use App\Models\CustomerGift;
use App\Models\Milestone;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerMilestoneFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\MilestoneFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Database\Eloquent\Collection;

class CustomerGiftService
{

    protected $customerGift;

    public function __construct()
    {
        $this->customerGift = new CustomerGift();
    }
    /**
     * Get customerGift using id
     *
     * @param  int $id
     *
     * @return CustomerGift
     */
    public function get(int $id): CustomerGift
    {
        return $this->customerGift->find($id);
    }

    /**
     * Get all customerGift
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customerGift->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->customerGift->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $customerGift
     * @return CustomerGift
     */
    public function create(array $customerGift): CustomerGift
    {
        return $this->customerGift->create($customerGift);
    }
    /**
     * Update customerGift
     *
     * @param CustomerGift $customerGift
     * @param array $data
     *
     *
     */
    public function update(CustomerGift $customerGift, array $data)
    {
        return  $customerGift->update($this->edit($customerGift, $data));
    }
    /**
     * Edit customerGift
     *
     * @param mMilestone $customerGift
     * @param array $data
     *
     * @return array
     */
    protected function edit(CustomerGift $customerGift, array $data): array
    {
        return array_merge($customerGift->toArray(), $data);
    }
    /**
     * Delete a customerGift
     *
     * @param CustomerGift $customerGift
     *
     *
     */
    public function delete(CustomerGift $customerGift)
    {
        return $customerGift->delete();
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->customerGift->getByCustomerId($id);
    }

    /**
     * getCollectedCustomersIds
     *
     * @param  mixed $id
     * @return void
     */
    public function getCollectedCustomersIds($id)
    {
        return $this->customerGift->getCollectedCustomersIds($id);
    }

    /**
     * getCollectedIdsByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getCollectedIdsByCustomerId($id)
    {
        return $this->customerGift->getCollectedIdsByCustomerId($id);
    }




}
