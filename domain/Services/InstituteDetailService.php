<?php

namespace domain\Services;

use App\Models\InstituteDetail;
use Illuminate\Database\Eloquent\Collection;

class InstituteDetailService
{


    protected $instituteDetail;

    public function __construct()
    {
        $this->instituteDetail = new InstituteDetail();
    }
    /**
     * Get instituteDetail using id
     *
     * @param  int $id
     *
     * @return InstituteDetail
     */
    public function get(int $id): InstituteDetail
    {
        return $this->instituteDetail->find($id);
    }
    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->instituteDetail->all();
    }
    /**
     * create
     *
     * @param  mixed $instituteDetail
     * @return InstituteDetail
     */
    public function create(array $instituteDetail): InstituteDetail
    {
        return $this->instituteDetail->create($instituteDetail);
    }

    /**
     * update
     *
     * @param  mixed $instituteDetail
     * @param  mixed $data
     *
     */
    public function update(InstituteDetail $instituteDetail, array $data)
    {
        return $instituteDetail->update($this->edit($instituteDetail, $data));
    }
    /**
     * Edit instituteDetail
     *
     * @param InstituteDetail $instituteDetail
     * @param array $data
     *
     * @return array
     */
    protected function edit(InstituteDetail $instituteDetail, array $data): array
    {
        return array_merge($instituteDetail->toArray(), $data);
    }
    /**
     * Delete a instituteDetail
     *
     * @param InstituteDetail $instituteDetail
     *
     * @return void
     */
    public function delete(InstituteDetail $instituteDetail): void
    {
        $instituteDetail->delete();
    }
    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return InstituteDetail
     */
    public function getByCustomerId($id): ?InstituteDetail
    {
        return $this->instituteDetail->getByCustomerId($id);
    }
}
