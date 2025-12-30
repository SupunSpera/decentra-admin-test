<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ItemDirectCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'referral_id',
        'purchased_amount',
        'commission_percentage',
        'commission_amount',
        'status'
    ];

    /**
     * customer
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * referral
     *
     * @return void
     */
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }

    /**
     * getByReferralId
     *
     * @param  mixed $referral_id
     * @return Collection
     */
    public function getByReferralId($referral_id): ?Collection
    {
        return $this->where('referral_id', $referral_id)
            ->with(['customer']) // Eager load referral's customer details
            ->get();
    }
}
