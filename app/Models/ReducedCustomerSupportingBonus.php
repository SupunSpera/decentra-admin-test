<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReducedCustomerSupportingBonus extends Model
{
    use HasFactory;

    const SIDE = [
        'LEFT' => 1,
        'RIGHT' => 2

    ];

    const STATUS = [
        'AVAILABLE' => 1,
        'UNAVAILABLE' => 0,
    ];

    protected $fillable = [
        'customer_id',
        'amount',
        'side',
        'status'
    ];


    /**
     * getAvailableReducedSupportingBonusByCustomerAndSide
     *
     * @param  mixed $customer
     * @param  mixed $side
     * @return void
     */
    public function getAvailableReducedSupportingBonusByCustomerAndSide($customer, $side)
    {
        return $this->where('customer_id', $customer)
            ->where('side', $side)
            ->where('status', '=', self::STATUS['AVAILABLE'])
            ->first();
    }
}
