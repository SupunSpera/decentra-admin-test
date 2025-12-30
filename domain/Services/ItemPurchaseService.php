<?php

namespace domain\Services;

use App\Models\ItemPurchase;
use Illuminate\Database\Eloquent\Collection;


class ItemPurchaseService
{

    protected $itemPurchase;

    public function __construct()
    {
        $this->itemPurchase = new ItemPurchase();
    }
    /**
     * Get itemPurchase using id
     *
     * @param  int $id
     *
     * @return ItemPurchase
     */
    public function get(int $id): ItemPurchase
    {
        return $this->itemPurchase->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->itemPurchase->all();
    }
    /**
     * create
     *
     * @param  mixed $itemPurchase
     * @return ItemPurchase
     */
    public function create(array $itemPurchase): ItemPurchase
    {
        return $this->itemPurchase->create($itemPurchase);
    }
    /**
     * Update itemPurchase
     *
     * @param ItemPurchase $itemPurchase
     * @param array $data
     *
     * @return void
     */
    public function update(ItemPurchase $itemPurchase, array $data): void
    {
        $itemPurchase->update($this->edit($itemPurchase, $data));
    }
    /**
     * Edit itemPurchase
     *
     * @param ItemPurchase $itemPurchase
     * @param array $data
     *
     * @return array
     */
    protected function edit(ItemPurchase $itemPurchase, array $data): array
    {
        return array_merge($itemPurchase->toArray(), $data);
    }
    /**
     * Delete a itemPurchase
     *
     * @param ItemPurchase $itemPurchase
     *
     * @return void
     */
    public function delete(ItemPurchase $itemPurchase): void
    {
        $itemPurchase->delete();
    }

    /**
     * getPurchasedTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedTotalByDate($date){
        return $this->itemPurchase->getPurchasedTotalByDate($date);
    }

    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date){
        return $this->itemPurchase->getPurchasedPointsTotalByDate($date);
    }
}
