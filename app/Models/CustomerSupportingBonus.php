<?php

namespace App\Models;

use Carbon\Carbon;
use domain\Facades\ReferralFacade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomerSupportingBonus extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'CONVERTED' => 1
    ];

    protected $fillable = [
        'customer_id',
        'referral_id',
        'amount',
        'status'
    ];



    /**
     * getTodaySupportingBonusReferrals
     *
     * @return Collection
     */
    public function getTodaySupportingBonusReferrals() : Collection
    {
        return $this->select('referral_id')
        ->distinct()
        ->where('status',self::STATUS['PENDING'])
        ->whereDate('created_at', Carbon::today())
        ->pluck('referral_id');
    }


    /**
     * getTodaySupportingBonuses
     *
     * @return Collection
     */
    public function getTodaySupportingBonuses() : Collection
    {
        return $this->whereDate('created_at', Carbon::today())
        ->where('status',self::STATUS['PENDING'])
        ->get();
    }


    /**
     * getTodaySupportingBonusesTotal
     *
     * @return float
     */
    public function getTodaySupportingBonusesTotal() : float
    {
        return $this->whereDate('created_at', Carbon::today())
        ->where('status',self::STATUS['PENDING'])
        ->sum('amount');
    }

    /**
     * getTodaySupportingBonusTotalByReferralAndCustomers
     *
     * @param  mixed $referral
     * @param  mixed $customers
     * @return void
     */
    public function getTodaySupportingBonusTotalByReferralAndCustomers($referral,$customers)
    {
        return $this->where('referral_id', $referral)
        ->whereIn('customer_id', $customers)
        ->whereDate('created_at', '=', Carbon::today())
        ->where('status',self::STATUS['PENDING'])
        ->sum('amount');
    }


}
