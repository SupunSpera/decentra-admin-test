<?php

namespace domain\Services;

use App\Models\InstituteMember;
use Illuminate\Database\Eloquent\Collection;

class InstituteMemberService
{


    protected $instituteMember;

    public function __construct()
    {
        $this->instituteMember = new InstituteMember();
    }
    /**
     * Get instituteMember using id
     *
     * @param  int $id
     *
     * @return InstituteMember
     */
    public function get(int $id): InstituteMember
    {
        return $this->instituteMember->find($id);
    }

    /**
     * getByEmail
     *
     * @param  mixed $email
     * @return void
     */
    public function getByEmail($email)
    {
        return $this->instituteMember->getByEmail($email);
    }
    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->instituteMember->all();
    }
    /**
     * create
     *
     * @param  mixed $instituteMember
     * @return InstituteMember
     */
    public function create(array $instituteMember): InstituteMember
    {
        return $this->instituteMember->create($instituteMember);
    }

    /**
     * update
     *
     * @param  mixed $instituteMember
     * @param  mixed $data
     *
     */
    public function update(InstituteMember $instituteMember, array $data)
    {
       return $instituteMember->update($this->edit($instituteMember, $data));
    }
    /**
     * Edit instituteMember
     *
     * @param InstituteMember $instituteMember
     * @param array $data
     *
     * @return array
     */
    protected function edit(InstituteMember $instituteMember, array $data): array
    {
        return array_merge($instituteMember->toArray(), $data);
    }
    /**
     * Delete a instituteMember
     *
     * @param InstituteMember $instituteMember
     *
     * @return void
     */
    public function delete(InstituteMember $instituteMember): void
    {
        $instituteMember->delete();
    }


    /**
     * getByType
     *
     * @param  mixed $type
     * @return void
     */
    public function getByTypeAndStatus($type,$status)
    {
        return $this->instituteMember->getByTypeAndStatus($type,$status);
    }


    /**
     * findMemberInPendingStatus
     *
     * @param  mixed $email
     * @return void
     */
    public function findMemberInPendingStatus($email)
    {
        return $this->instituteMember->findMemberInPendingStatus($email);
    }





}
