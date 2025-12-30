<?php

namespace domain\Services;

use App\Models\DailyTotalShare;
use Illuminate\Database\Eloquent\Collection;


class DailyTotalShareService
{

    protected $dailyTotalShare;

    public function __construct()
    {
        $this->dailyTotalShare = new DailyTotalShare();
    }

    /**
     * Get dailyTotalShare using id
     *
     * @param  int $id
     *
     * @return DailyTotalShare
     */
    public function get(int $id): DailyTotalShare
    {
        return $this->dailyTotalShare->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->dailyTotalShare->all();
    }
    /**
     * create
     *
     * @param  mixed $dailyTotalShare
     * @return DailyTotalShare
     */
    public function create(array $dailyTotalShare): DailyTotalShare
    {
        return $this->dailyTotalShare->create($dailyTotalShare);
    }
    /**
     * Update dailyTotalShare
     *
     * @param DailyTotalShare $dailyTotalShare
     * @param array $data
     *
     * @return void
     */
    public function update(DailyTotalShare $dailyTotalShare, array $data): void
    {
        $dailyTotalShare->update($this->edit($dailyTotalShare, $data));
    }
    /**
     * Edit dailyTotalShare
     *
     * @param DailyTotalShare $dailyTotalShare
     * @param array $data
     *
     * @return array
     */
    protected function edit(DailyTotalShare $dailyTotalShare, array $data): array
    {
        return array_merge($dailyTotalShare->toArray(), $data);
    }
    /**
     * Delete a dailyTotalShare
     *
     * @param DailyTotalShare $dailyTotalShare
     *
     * @return void
     */
    public function delete(DailyTotalShare $dailyTotalShare): void
    {
        $dailyTotalShare->delete();
    }

    /**
     * getByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByDate($date){
        return $this->dailyTotalShare->getByDate($date);
    }

    /**
     * getByTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByTotalByDate($date){
        return $this->dailyTotalShare->getByTotalByDate($date);
    }
}
