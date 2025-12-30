<?php

namespace domain\Services;


use App\Models\ConnectedCustomer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class ConnectedCustomerService
{

    protected $connected_customer;

    public function __construct()
    {
        $this->connected_customer = new ConnectedCustomer();
    }
    /**
     * Get connected_customer using id
     *
     * @param  int $id
     *
     * @return ConnectedCustomer
     */
    public function get(int $id): ConnectedCustomer
    {
        return $this->connected_customer->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->connected_customer->all();
    }
    /**
     * create
     *
     * @param  mixed $connected_customer
     * @return ConnectedCustomer
     */
    public function create(array $connected_customer): ConnectedCustomer
    {
        return $this->connected_customer->create($connected_customer);
    }
    /**
     * Update connected_customer
     *
     * @param ConnectedCustomer $connected_customer
     * @param array $data
     *
     *
     */
    public function update(ConnectedCustomer $connected_customer, array $data)
    {
        return  $connected_customer->update($this->edit($connected_customer, $data));
    }
    /**
     * Edit connected_customer
     *
     * @param ConnectedCustomer $connected_customer
     * @param array $data
     *
     * @return array
     */
    protected function edit(ConnectedCustomer $connected_customer, array $data): array
    {
        return array_merge($connected_customer->toArray(), $data);
    }
    /**
     * Delete a connected_customer
     *
     * @param ConnectedCustomer $connected_customer
     *
     *
     */
    public function delete(ConnectedCustomer $connected_customer)
    {
        return $connected_customer->delete();
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $email
     * @return ConnectedCustomer
     */
    public function getByCustomerId($email): ?ConnectedCustomer
    {
        return $this->connected_customer->getByCustomerId($email);
    }



}
