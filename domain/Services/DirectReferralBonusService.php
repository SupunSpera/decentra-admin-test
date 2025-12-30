<?php

namespace domain\Services;

use App\Models\DirectReferralBonus;
use Illuminate\Database\Eloquent\Collection;

class DirectReferralBonusService
{

    protected $directReferralBonus;

    public function __construct()
    {
        $this->directReferralBonus = new DirectReferralBonus();
    }
    /**
     * Get directReferralBonus using id
     *
     * @param  int $id
     *
     * @return DirectReferralBonus
     */
    public function get(int $id): DirectReferralBonus
    {
        return $this->directReferralBonus->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->directReferralBonus->all();
    }
    /**
     * create
     *
     * @param  mixed $directReferralBonus
     * @return DirectReferralBonus
     */
    public function create(array $directReferralBonus): DirectReferralBonus
    {
        return $this->directReferralBonus->create($directReferralBonus);
    }
    /**
     * Update directReferralBonus
     *
     * @param DirectReferralBonus $directReferralBonus
     * @param array $data
     *
     *
     */
    public function update(DirectReferralBonus $directReferralBonus, array $data)
    {
        return  $directReferralBonus->update($this->edit($directReferralBonus, $data));
    }
    /**
     * Edit directReferralBonus
     *
     * @param DirectReferralBonus $directReferralBonus
     * @param array $data
     *
     * @return array
     */
    protected function edit(DirectReferralBonus $directReferralBonus, array $data): array
    {
        return array_merge($directReferralBonus->toArray(), $data);
    }
    /**
     * Delete a directReferralBonus
     *
     * @param DirectReferralBonus $directReferralBonus
     *
     *
     */
    public function delete(DirectReferralBonus $directReferralBonus)
    {
        return $directReferralBonus->delete();
    }


    /**
     * getTodayTotalByReferralId
     *
     * @param  mixed $id
     * @return void
     */
    public function getTodayTotalByReferralId($id)
    {
       return $this->directReferralBonus->getTodayTotalByReferralId($id);
    }

}
