<?php

namespace domain\Services;


use App\Models\UrbxWallet;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class URBXWalletService
{

    protected $urbx_wallet;

    public function __construct()
    {
        $this->urbx_wallet = new UrbxWallet();
    }
    /**
     * Get urbx_wallet using id
     *
     * @param  int $id
     *
     * @return UrbxWallet
     */
    public function get(int $id): UrbxWallet
    {
        return $this->urbx_wallet->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->urbx_wallet->all();
    }
    /**
     * create
     *
     * @param  mixed $urbx_wallet
     * @return UrbxWallet
     */
    public function create(array $urbx_wallet): UrbxWallet
    {
        return $this->urbx_wallet->create($urbx_wallet);
    }
    /**
     * Update urbx_wallet
     *
     * @param UrbxWallet $urbx_wallet
     * @param array $data
     *
     *
     */
    public function update(UrbxWallet $urbx_wallet, array $data)
    {
        return  $urbx_wallet->update($this->edit($urbx_wallet, $data));
    }
    /**
     * Edit urbx_wallet
     *
     * @param UrbxWallet $urbx_wallet
     * @param array $data
     *
     * @return array
     */
    protected function edit(UrbxWallet $urbx_wallet, array $data): array
    {
        return array_merge($urbx_wallet->toArray(), $data);
    }
    /**
     * Delete a urbx_wallet
     *
     * @param UrbxWallet $urbx_wallet
     *
     *
     */
    public function delete(UrbxWallet $urbx_wallet)
    {
        return $urbx_wallet->delete();
    }

    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->urbx_wallet->getByCustomerId($id);
    }


}
