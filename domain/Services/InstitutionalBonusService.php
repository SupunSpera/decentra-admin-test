<?php

namespace domain\Services;

use App\Models\InstitutionalBonus;


use Illuminate\Database\Eloquent\Collection;

class InstitutionalBonusService
{

    protected $institutionalBonus;

    public function __construct()
    {
        $this->institutionalBonus = new InstitutionalBonus();
    }
    /**
     * Get institutionalBonus using id
     *
     * @param  int $id
     *
     * @return InstitutionalBonus
     */
    public function get(int $id): InstitutionalBonus
    {
        return $this->institutionalBonus->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->institutionalBonus->all();
    }
    /**
     * create
     *
     * @param  mixed $institutionalBonus
     * @return InstitutionalBonus
     */
    public function create(array $institutionalBonus): InstitutionalBonus
    {
        return $this->institutionalBonus->create($institutionalBonus);
    }
    /**
     * Update institutionalBonus
     *
     * @param InstitutionalBonus $institutionalBonus
     * @param array $data
     *
     *
     */
    public function update(InstitutionalBonus $institutionalBonus, array $data)
    {
        return  $institutionalBonus->update($this->edit($institutionalBonus, $data));
    }
    /**
     * Edit institutionalBonus
     *
     * @param InstitutionalBonus $institutionalBonus
     * @param array $data
     *
     * @return array
     */
    protected function edit(InstitutionalBonus $institutionalBonus, array $data): array
    {
        return array_merge($institutionalBonus->toArray(), $data);
    }
    /**
     * Delete a institutionalBonus
     *
     * @param InstitutionalBonus $institutionalBonus
     *
     *
     */
    public function delete(InstitutionalBonus $institutionalBonus)
    {
        return $institutionalBonus->delete();
    }

    /**
     * getTotalAmount
     *
     * @return void
     */
    public function getTotalAmount()
    {
        return $this->institutionalBonus->getTotalAmount();
    }

}
