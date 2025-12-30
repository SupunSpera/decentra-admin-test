<?php

namespace domain\Services;

use App\Models\ProjectDirectCommission;
use Illuminate\Database\Eloquent\Collection;

class ProjectDirectCommissionService
{

    protected $projectDirectCommission;

    public function __construct()
    {
        $this->projectDirectCommission = new ProjectDirectCommission();
    }
    /**
     * Get projectDirectCommission using id
     *
     * @param  int $id
     *
     * @return ProjectDirectCommission
     */
    public function get(int $id): ProjectDirectCommission
    {
        return $this->projectDirectCommission->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->projectDirectCommission->all();
    }
    /**
     * create
     *
     * @param  mixed $projectDirectCommission
     * @return ProjectDirectCommission
     */
    public function create(array $projectDirectCommission): ProjectDirectCommission
    {
        return $this->projectDirectCommission->create($projectDirectCommission);
    }
    /**
     * Update projectDirectCommission
     *
     * @param ProjectDirectCommission $projectDirectCommission
     * @param array $data
     *
     *
     */
    public function update(ProjectDirectCommission $projectDirectCommission, array $data)
    {
        return  $projectDirectCommission->update($this->edit($projectDirectCommission, $data));
    }
    /**
     * Edit projectDirectCommission
     *
     * @param ProjectDirectCommission $projectDirectCommission
     * @param array $data
     *
     * @return array
     */
    protected function edit(ProjectDirectCommission $projectDirectCommission, array $data): array
    {
        return array_merge($projectDirectCommission->toArray(), $data);
    }
    /**
     * Delete a projectDirectCommission
     *
     * @param ProjectDirectCommission $projectDirectCommission
     *
     *
     */
    public function delete(ProjectDirectCommission $projectDirectCommission)
    {
        return $projectDirectCommission->delete();
    }


    /**
     * getTodayTotalByReferralId
     *
     * @param  mixed $id
     * @return void
     */
    public function getTodayTotalByReferralId($id)
    {
       return $this->projectDirectCommission->getTodayTotalByReferralId($id);
    }

}
