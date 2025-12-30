<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'nic',
        'birthday',
        'address_1',
        'address_2',
        'country'

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

}
