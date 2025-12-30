<?php

namespace domain\Services;

use App\Models\GeneratedSupportingBonus;
use Illuminate\Database\Eloquent\Collection;


class GeneratedSupportingBonusService
{

    protected $generatedSupportingBonus;

    public function __construct()
    {
        $this->generatedSupportingBonus = new GeneratedSupportingBonus();
    }

    /**
     * Get generatedSupportingBonus using id
     *
     * @param  int $id
     *
     * @return GeneratedSupportingBonus
     */
    public function get(int $id): GeneratedSupportingBonus
    {
        return $this->generatedSupportingBonus->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->generatedSupportingBonus->all();
    }
    /**
     * create
     *
     * @param  mixed $generatedSupportingBonus
     * @return GeneratedSupportingBonus
     */
    public function create(array $generatedSupportingBonus): GeneratedSupportingBonus
    {
        return $this->generatedSupportingBonus->create($generatedSupportingBonus);
    }
    /**
     * Update generatedSupportingBonus
     *
     * @param GeneratedSupportingBonus $generatedSupportingBonus
     * @param array $data
     *
     * @return void
     */
    public function update(GeneratedSupportingBonus $generatedSupportingBonus, array $data): void
    {
        $generatedSupportingBonus->update($this->edit($generatedSupportingBonus, $data));
    }
    /**
     * Edit generatedSupportingBonus
     *
     * @param GeneratedSupportingBonus $generatedSupportingBonus
     * @param array $data
     *
     * @return array
     */
    protected function edit(GeneratedSupportingBonus $generatedSupportingBonus, array $data): array
    {
        return array_merge($generatedSupportingBonus->toArray(), $data);
    }
    /**
     * Delete a generatedSupportingBonus
     *
     * @param GeneratedSupportingBonus $generatedSupportingBonus
     *
     * @return void
     */
    public function delete(GeneratedSupportingBonus $generatedSupportingBonus): void
    {
        $generatedSupportingBonus->delete();
    }


    /**
     * getTodayTotalByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getTodayTotalByCustomerId($id)
    {
       return $this->generatedSupportingBonus->getTodayTotalByCustomerId($id);
    }

    /**
     * getNotVIewedPreviousRewards
     *
     * @return void
     */
    public function getNotVIewedPreviousRewards()
    {
       return $this->generatedSupportingBonus->getNotVIewedPreviousRewards();
    }

}
