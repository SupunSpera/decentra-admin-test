<?php

namespace domain\Services;

use App\Models\ItemDirectCommission;
use Illuminate\Database\Eloquent\Collection;

class ItemDirectCommissionService
{

    protected $itemDirectComission;

    public function __construct()
    {
        $this->itemDirectComission = new ItemDirectCommission();
    }
    /**
     * Get itemDirectComission using id
     *
     * @param  int $id
     *
     * @return ItemDirectCommission
     */
    public function get(int $id): ItemDirectCommission
    {
        return $this->itemDirectComission->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->itemDirectComission->all();
    }
    /**
     * create
     *
     * @param  mixed $itemDirectComission
     * @return ItemDirectCommission
     */
    public function create(array $itemDirectComission): ItemDirectCommission
    {
        return $this->itemDirectComission->create($itemDirectComission);
    }
    /**
     * Update itemDirectComission
     *
     * @param ItemDirectCommission $itemDirectComission
     * @param array $data
     *
     *
     */
    public function update(ItemDirectCommission $itemDirectComission, array $data)
    {
        return  $itemDirectComission->update($this->edit($itemDirectComission, $data));
    }
    /**
     * Edit itemDirectComission
     *
     * @param ItemDirectCommission $itemDirectComission
     * @param array $data
     *
     * @return array
     */
    protected function edit(ItemDirectCommission $itemDirectComission, array $data): array
    {
        return array_merge($itemDirectComission->toArray(), $data);
    }
    /**
     * Delete a itemDirectComission
     *
     * @param ItemDirectCommission $itemDirectComission
     *
     *
     */
    public function delete(ItemDirectCommission $itemDirectComission)
    {
        return $itemDirectComission->delete();
    }


    /**
     * getByReferralId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByReferralId($referral_id)
    {
       return $this->itemDirectComission->getByReferralId($referral_id);
    }

}
