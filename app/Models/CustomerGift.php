<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerGift extends Model
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
        'gift_id',
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
     * getCollectedIdsByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getCollectedIdsByCustomerId($id)
    {
        return $this->where('customer_id', $id)->pluck('gift_id');
    }


    /**
     * getCollectedCustomersIds
     *
     * @param  mixed $id
     * @return void
     */
    public function getCollectedCustomersIds($id)
    {
        return $this->where('milestone_id', $id)->pluck('customer_id');
    }
}
