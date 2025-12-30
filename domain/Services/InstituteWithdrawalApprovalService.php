<?php

namespace domain\Services;

use App\Models\InstituteWithdrawalApproval;
use Illuminate\Database\Eloquent\Collection;

class InstituteWithdrawalApprovalService
{


    protected $instituteWithdrawalApproval;

    public function __construct()
    {
        $this->instituteWithdrawalApproval = new InstituteWithdrawalApproval();
    }
    /**
     * Get instituteWithdrawalApproval using id
     *
     * @param  int $id
     *
     * @return InstituteWithdrawalApproval
     */
    public function get(int $id): InstituteWithdrawalApproval
    {
        return $this->instituteWithdrawalApproval->find($id);
    }



    /**
     * all
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->instituteWithdrawalApproval->all();
    }
    /**
     * create
     *
     * @param  mixed $instituteWithdrawalApproval
     * @return InstituteWithdrawalApproval
     */
    public function create(array $instituteWithdrawalApproval): InstituteWithdrawalApproval
    {
        return $this->instituteWithdrawalApproval->create($instituteWithdrawalApproval);
    }

    /**
     * update
     *
     * @param  mixed $instituteWithdrawalApproval
     * @param  mixed $data
     *
     */
    public function update(InstituteWithdrawalApproval $instituteWithdrawalApproval, array $data)
    {
       return $instituteWithdrawalApproval->update($this->edit($instituteWithdrawalApproval, $data));
    }
    /**
     * Edit instituteWithdrawalApproval
     *
     * @param InstituteWithdrawalApproval $instituteWithdrawalApproval
     * @param array $data
     *
     * @return array
     */
    protected function edit(InstituteWithdrawalApproval $instituteWithdrawalApproval, array $data): array
    {
        return array_merge($instituteWithdrawalApproval->toArray(), $data);
    }
    /**
     * Delete a instituteWithdrawalApproval
     *
     * @param InstituteWithdrawalApproval $instituteWithdrawalApproval
     *
     * @return void
     */
    public function delete(InstituteWithdrawalApproval $instituteWithdrawalApproval): void
    {
        $instituteWithdrawalApproval->delete();
    }


    /**
     * getByType
     *
     * @param  mixed $type
     * @return void
     */
    public function getByTypeAndStatus($type,$status)
    {
        return $this->instituteWithdrawalApproval->getByTypeAndStatus($type,$status);
    }


    /**
     * findMemberInPendingStatus
     *
     * @param  mixed $email
     * @return void
     */
    public function findMemberInPendingStatus($email)
    {
        return $this->instituteWithdrawalApproval->findMemberInPendingStatus($email);
    }





}
