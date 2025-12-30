<?php

namespace domain\Services;

use App\Models\FrozenToken;
use Illuminate\Database\Eloquent\Collection;

class FrozenTokenService
{

    protected $frozenToken;

    public function __construct()
    {
        $this->frozenToken = new FrozenToken();
    }
    /**
     * Get frozenToken using id
     *
     * @param  int $id
     *
     * @return FrozenToken
     */
    public function get(int $id): FrozenToken
    {
        return $this->frozenToken->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->frozenToken->all();
    }
    /**
     * create
     *
     * @param  mixed $frozenToken
     * @return FrozenToken
     */
    public function create(array $frozenToken): FrozenToken
    {
        return $this->frozenToken->create($frozenToken);
    }
    /**
     * Update frozenToken
     *
     * @param FrozenToken $frozenToken
     * @param array $data
     *
     *
     */
    public function update(FrozenToken $frozenToken, array $data)
    {
        return  $frozenToken->update($this->edit($frozenToken, $data));
    }
    /**
     * Edit frozenToken
     *
     * @param FrozenToken $frozenToken
     * @param array $data
     *
     * @return array
     */
    protected function edit(FrozenToken $frozenToken, array $data): array
    {
        return array_merge($frozenToken->toArray(), $data);
    }
    /**
     * Delete a frozenToken
     *
     * @param FrozenToken $frozenToken
     *
     *
     */
    public function delete(FrozenToken $frozenToken)
    {
        return $frozenToken->delete();
    }


    /**
     * getByWalletAndStatus
     *
     * @param  mixed $walletId
     * @return void
     */
    public function getByWalletAndStatus($walletId)
    {
        return $this->frozenToken->getByWalletAndStatus($walletId);
    }


}
