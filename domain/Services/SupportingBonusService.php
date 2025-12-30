<?php

namespace domain\Services;

use App\Models\SupportingBonus;
use Illuminate\Database\Eloquent\Collection;

class SupportingBonusService
{

    protected $supportingBonus;

    public function __construct()
    {
        $this->supportingBonus = new SupportingBonus();
    }
    /**
     * Get supportingBonus using id
     *
     * @param  int $id
     *
     * @return SupportingBonus
     */
    public function get(int $id): SupportingBonus
    {
        return $this->supportingBonus->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->supportingBonus->all();
    }
    /**
     * create
     *
     * @param  mixed $supportingBonus
     * @return SupportingBonus
     */
    public function create(array $supportingBonus): SupportingBonus
    {
        return $this->supportingBonus->create($supportingBonus);
    }
    /**
     * Update supportingBonus
     *
     * @param SupportingBonus $supportingBonus
     * @param array $data
     *
     *
     */
    public function update(SupportingBonus $supportingBonus, array $data)
    {
        return  $supportingBonus->update($this->edit($supportingBonus, $data));
    }
    /**
     * Edit supportingBonus
     *
     * @param SupportingBonus $supportingBonus
     * @param array $data
     *
     * @return array
     */
    protected function edit(SupportingBonus $supportingBonus, array $data): array
    {
        return array_merge($supportingBonus->toArray(), $data);
    }
    /**
     * Delete a supportingBonus
     *
     * @param SupportingBonus $supportingBonus
     *
     *
     */
    public function delete(SupportingBonus $supportingBonus)
    {
        return $supportingBonus->delete();
    }


    /**
     * getTotalAmount
     *
     * @return void
     */
    public function getTotalAmount()
    {
        return $this->supportingBonus->getTotalAmount();
    }



}
