<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDirectCommission extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'referral_id',
        'invested_amount',
        'commission_percentage',
        'commission_amount',
        'status'
    ];


    /**
     * getTodayTotalByReferralId
     *
     * @param  mixed $referralId
     * @return void
     */
    function getTodayTotalByReferralId($referralId){
        return $this->where('referral_id', $referralId)
        ->whereDate('created_at', Carbon::today())
        ->sum('commission_amount');
    }
}
