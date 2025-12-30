<?php

namespace domain\Services;

use App\Models\SwapTransaction;
use Illuminate\Database\Eloquent\Collection;

class SwapTransactionService
{

    protected $walletTransaction;

    public function __construct()
    {
        $this->walletTransaction = new SwapTransaction();
    }
    /**
     * Get walletTransaction using id
     *
     * @param  int $id
     *
     * @return SwapTransaction
     */
    public function get(int $id): SwapTransaction
    {
        return $this->walletTransaction->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->walletTransaction->all();
    }
    /**
     * create
     *
     * @param  mixed $walletTransaction
     * @return SwapTransaction
     */
    public function create(array $walletTransaction): SwapTransaction
    {
        return $this->walletTransaction->create($walletTransaction);
    }
    /**
     * Update walletTransaction
     *
     * @param SwapTransaction $walletTransaction
     * @param array $data
     *
     *
     */
    public function update(SwapTransaction $walletTransaction, array $data)
    {
        return  $walletTransaction->update($this->edit($walletTransaction, $data));
    }
    /**
     * Edit walletTransaction
     *
     * @param SwapTransaction $walletTransaction
     * @param array $data
     *
     * @return array
     */
    protected function edit(SwapTransaction $walletTransaction, array $data): array
    {
        return array_merge($walletTransaction->toArray(), $data);
    }
    /**
     * Delete a walletTransaction
     *
     * @param SwapTransaction $walletTransaction
     *
     *
     */
    public function delete(SwapTransaction $walletTransaction)
    {
        return $walletTransaction->delete();
    }


}
