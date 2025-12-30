<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPurchase extends Model
{
    use HasFactory;

    const STATUS = [
        'AVAILABLE' => 1,
        'UNAVAILABLE' => 0,
    ];

    protected $fillable = [
        'customer_id',
        'item_id',
        'amount',
        'points',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    /**
     * getPurchasedTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedTotalByDate($date)
    {
        return $this->whereDate('created_at', $date)
            ->sum('amount');
    }

    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date){
        return $this->whereDate('created_at', $date)
        ->sum('points');
    }
}
