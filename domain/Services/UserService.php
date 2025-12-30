<?php

namespace domain\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{


    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }
    /**
     * Get user using id
     *
     * @param  int $id
     *
     * @return User
     */
    public function get(int $id): User
    {
        return $this->user->find($id);
    }
    /**
     * getByEmail
     *
     * @param  mixed $email
     * @return User
     */
    public function getByEmail($email): ?User
    {
        return $this->user->getByEmail($email);
    }
    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->user->all();
    }
    /**
     * create
     *
     * @param  mixed $user
     * @return User
     */
    public function create(array $user): User
    {
        return $this->user->create($user);
    }

    /**
     * update
     *
     * @param  mixed $user
     * @param  mixed $data
     *
     */
    public function update(User $user, array $data)
    {
       return $user->update($this->edit($user, $data));
    }
    /**
     * Edit user
     *
     * @param User $user
     * @param array $data
     *
     * @return array
     */
    protected function edit(User $user, array $data): array
    {
        return array_merge($user->toArray(), $data);
    }
    /**
     * Delete a user
     *
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user): void
    {
        $user->delete();
    }


}
