<?php

namespace domain\Services;

use App\Models\Investment;

use Illuminate\Database\Eloquent\Collection;

class InvestmentService
{

    protected $investment;

    public function __construct()
    {
        $this->investment = new Investment();
    }
    /**
     * Get investment using id
     *
     * @param  int $id
     *
     * @return Investment
     */
    public function get(int $id): Investment
    {
        return $this->investment->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->investment->all();
    }
    /**
     * create
     *
     * @param  mixed $investment
     * @return Investment
     */
    public function create(array $investment): Investment
    {
        return $this->investment->create($investment);
    }
    /**
     * Update investment
     *
     * @param Investment $investment
     * @param array $data
     *
     *
     */
    public function update(Investment $investment, array $data)
    {
        return  $investment->update($this->edit($investment, $data));
    }
    /**
     * Edit investment
     *
     * @param Investment $investment
     * @param array $data
     *
     * @return array
     */
    protected function edit(Investment $investment, array $data): array
    {
        return array_merge($investment->toArray(), $data);
    }
    /**
     * Delete a investment
     *
     * @param Investment $investment
     *
     *
     */
    public function delete(Investment $investment)
    {
        return $investment->delete();
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->investment->getByCustomerId($id);
    }


}
