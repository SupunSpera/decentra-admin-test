<?php

namespace domain\Services;

use App\Models\ProjectInvestment;
use Illuminate\Database\Eloquent\Collection;


class ProjectInvestmentService
{

    protected $projectInvestment;

    public function __construct()
    {
        $this->projectInvestment = new ProjectInvestment();
    }

    /**
     * Get projectInvestment using id
     *
     * @param  int $id
     *
     * @return ProjectInvestment
     */
    public function get(int $id): ProjectInvestment
    {
        return $this->projectInvestment->find($id);
    }

    /**
     * Get all projectInvestments
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->projectInvestment->all();
    }
    /**
     * create
     *
     * @param  mixed $projectInvestment
     * @return ProjectInvestment
     */
    public function create(array $projectInvestment): ProjectInvestment
    {
        return $this->projectInvestment->create($projectInvestment);
    }
    /**
     * Update projectInvestment
     *
     * @param ProjectInvestment $projectInvestment
     * @param array $data
     *
     * @return void
     */
    public function update(ProjectInvestment $projectInvestment, array $data): void
    {
        $projectInvestment->update($this->edit($projectInvestment, $data));
    }
    /**
     * Edit projectInvestment
     *
     * @param ProjectInvestment $projectInvestment
     * @param array $data
     *
     * @return array
     */
    protected function edit(ProjectInvestment $projectInvestment, array $data): array
    {
        return array_merge($projectInvestment->toArray(), $data);
    }
    /**
     * Delete a projectInvestment
     *
     * @param ProjectInvestment $projectInvestment
     *
     * @return void
     */
    public function delete(ProjectInvestment $projectInvestment): void
    {
        $projectInvestment->delete();
    }


    /**
     * getInvestmentTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getInvestmentTotalByDate($date){
        return $this->projectInvestment->getInvestmentTotalByDate($date);
    }

    /**
     * getInvestedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getInvestedPointsTotalByDate($date){
        return $this->projectInvestment->getInvestedPointsTotalByDate($date);
    }
}
