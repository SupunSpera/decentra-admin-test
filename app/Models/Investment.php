<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'liquidity_pool',
        'direct_referral_bonus',
        'supporting_bonus_pool',
        'institutional_bonus',
        'status'
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

}
