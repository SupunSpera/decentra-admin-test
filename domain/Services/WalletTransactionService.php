<?php

namespace domain\Services;

use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;

class WalletTransactionService
{

    protected $walletTransaction;

    public function __construct()
    {
        $this->walletTransaction = new WalletTransaction();
    }
    /**
     * Get walletTransaction using id
     *
     * @param  int $id
     *
     * @return WalletTransaction
     */
    public function get(int $id): WalletTransaction
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
     * @return WalletTransaction
     */
    public function create(array $walletTransaction): WalletTransaction
    {
        return $this->walletTransaction->create($walletTransaction);
    }
    /**
     * Update walletTransaction
     *
     * @param WalletTransaction $walletTransaction
     * @param array $data
     *
     *
     */
    public function update(WalletTransaction $walletTransaction, array $data)
    {
        return  $walletTransaction->update($this->edit($walletTransaction, $data));
    }
    /**
     * Edit walletTransaction
     *
     * @param WalletTransaction $walletTransaction
     * @param array $data
     *
     * @return array
     */
    protected function edit(WalletTransaction $walletTransaction, array $data): array
    {
        return array_merge($walletTransaction->toArray(), $data);
    }
    /**
     * Delete a walletTransaction
     *
     * @param WalletTransaction $walletTransaction
     *
     *
     */
    public function delete(WalletTransaction $walletTransaction)
    {
        return $walletTransaction->delete();
    }


}
