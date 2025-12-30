<?php

namespace domain\Services;


use App\Models\UrbxWalletRedeem;
use Illuminate\Database\Eloquent\Collection;


class URBXWalletRedeemService
{

    protected $urbxWalletRedeem;

    public function __construct()
    {
        $this->urbxWalletRedeem = new UrbxWalletRedeem();
    }
    /**
     * Get urbxWalletRedeem using id
     *
     * @param  int $id
     *
     * @return UrbxWalletRedeem
     */
    public function get(int $id): ?UrbxWalletRedeem
    {
        return $this->urbxWalletRedeem->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->urbxWalletRedeem->all();
    }
    /**
     * create
     *
     * @param  mixed $urbxWalletRedeem
     * @return UrbxWalletRedeem
     */
    public function create(array $urbxWalletRedeem): UrbxWalletRedeem
    {
        return $this->urbxWalletRedeem->create($urbxWalletRedeem);
    }
    /**
     * Update urbxWalletRedeem
     *
     * @param UrbxWalletRedeem $urbxWalletRedeem
     * @param array $data
     *
     *
     */
    public function update(UrbxWalletRedeem $urbxWalletRedeem, array $data)
    {
        return  $urbxWalletRedeem->update($this->edit($urbxWalletRedeem, $data));
    }
    /**
     * Edit urbxWalletRedeem
     *
     * @param UrbxWalletRedeem $urbxWalletRedeem
     * @param array $data
     *
     * @return array
     */
    protected function edit(UrbxWalletRedeem $urbxWalletRedeem, array $data): array
    {
        return array_merge($urbxWalletRedeem->toArray(), $data);
    }
    /**
     * Delete a urbxWalletRedeem
     *
     * @param UrbxWalletRedeem $urbxWalletRedeem
     *
     *
     */
    public function delete(UrbxWalletRedeem $urbxWalletRedeem)
    {
        return $urbxWalletRedeem->delete();
    }

}
