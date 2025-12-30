<?php

namespace domain\Services;

use App\Models\CurrencyPool;
use Illuminate\Database\Eloquent\Collection;

class CurrencyPoolService
{

    protected $currencyPool;

    public function __construct()
    {
        $this->currencyPool = new CurrencyPool();
    }
    /**
     * Get currencyPool using id
     *
     * @param  int $id
     *
     * @return CurrencyPool
     */
    public function get(int $id): CurrencyPool
    {
        return $this->currencyPool->find($id);
    }



    /**
     * getFirst
     *
     * @return CurrencyPool
     */
    public function getFirst(): CurrencyPool
    {
        return $this->currencyPool->first();
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->currencyPool->all();
    }
    /**
     * create
     *
     * @param  mixed $currencyPool
     * @return CurrencyPool
     */
    public function create(array $currencyPool): CurrencyPool
    {
        return $this->currencyPool->create($currencyPool);
    }
    /**
     * Update currencyPool
     *
     * @param CurrencyPool $currencyPool
     * @param array $data
     *
     *
     */
    public function update(CurrencyPool $currencyPool, array $data)
    {
        return  $currencyPool->update($this->edit($currencyPool, $data));
    }
    /**
     * Edit currencyPool
     *
     * @param CurrencyPool $currencyPool
     * @param array $data
     *
     * @return array
     */
    protected function edit(CurrencyPool $currencyPool, array $data): array
    {
        return array_merge($currencyPool->toArray(), $data);
    }
    /**
     * Delete a currencyPool
     *
     * @param CurrencyPool $currencyPool
     *
     *
     */
    public function delete(CurrencyPool $currencyPool)
    {
        return $currencyPool->delete();
    }


}
