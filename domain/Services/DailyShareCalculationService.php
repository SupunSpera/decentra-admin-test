<?php

namespace domain\Services;

use App\Models\DailyShareCalculation;
use App\Models\DailyTotalShare;
use Illuminate\Database\Eloquent\Collection;


class DailyShareCalculationService
{

    protected $dailyShareCalculation;

    public function __construct()
    {
        $this->dailyShareCalculation = new DailyShareCalculation();
    }

    /**
     * Get dailyTotalShare using id
     *
     * @param  int $id
     *
     * @return DailyShareCalculation
     */
    public function get(int $id): DailyShareCalculation
    {
        return $this->dailyShareCalculation->find($id);
    }

    /**
     * Get all productPurchases
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->dailyShareCalculation->all();
    }
    /**
     * create
     *
     * @param  mixed $dailyTotalShare
     * @return DailyShareCalculation
     */
    public function create(array $dailyShareCalculation): DailyShareCalculation
    {
        return $this->dailyShareCalculation->create($dailyShareCalculation);
    }
    /**
     * Update DailyShareCalculation
     *
     * @param DailyShareCalculation $DailyShareCalculation
     * @param array $data
     *
     * @return void
     */
    public function update(DailyShareCalculation $dailyShareCalculation, array $data): void
    {
        $dailyShareCalculation->update($this->edit($dailyShareCalculation, $data));
    }
    /**
     * Edit DailyShareCalculation
     *
     * @param DailyShareCalculation $DailyShareCalculation
     * @param array $data
     *
     * @return array
     */
    protected function edit(DailyShareCalculation $dailyShareCalculation, array $data): array
    {
        return array_merge($dailyShareCalculation->toArray(), $data);
    }
    /**
     * Delete a DailyShareCalculation
     *
     * @param DailyShareCalculation $DailyShareCalculation
     *
     * @return void
     */
    public function delete(DailyShareCalculation $dailyShareCalculation): void
    {
        $dailyShareCalculation->delete();
    }

    /**
     * getByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByDate($date){
        return $this->dailyShareCalculation->getByDate($date);
    }


    /**
     * getLastRecord
     *
     * @return void
     */
    public function getLastRecord()
    {
        return $this->dailyShareCalculation->getLastRecord();
    }

    public function calculateDailyShareRecord()
    {
      //code
    }

}
