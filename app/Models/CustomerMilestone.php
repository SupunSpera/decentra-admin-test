<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;


class CustomerMilestone extends Authenticatable
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'ACTIVE' => 1
    ];



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'milestone_id',
        'status'
    ];

    protected $hidden = [
        'password',
    ];


    /**
     * customer
     *
     * @return HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->where('customer_id', $id)->first();
    }


    /**
     * getArchivedIdsByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getArchivedIdsByCustomerId($id)
    {
        return $this->where('customer_id', $id)->pluck('milestone_id');
    }


    /**
     * getArchivedCustomersIds
     *
     * @param  mixed $id
     * @return void
     */
    public function getArchivedCustomersIds($id)
    {
        return $this->where('milestone_id', $id)->pluck('customer_id');
    }
}
