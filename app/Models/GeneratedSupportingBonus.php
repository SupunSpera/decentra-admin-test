<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedSupportingBonus extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'PAID' => 1
    ];

    const VIEW_STATUS = [
        'PENDING' => 0,
        'VIEWED' => 1
    ];

    protected $fillable = [
        'customer_id',
        'share_amount',
        'share_value',
        'commission',
        'view_status',
        'status'
    ];


    /**
     * getTodayTotalByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getTodayTotalByCustomerId($id)
    {
        return $this->where('customer_id', $id)
        ->whereDate('created_at', Carbon::today())
            ->sum('commission');
    }

    /**
     * getNotVIewedPreviousRewards
     *
     * @return void
     */
    public function getNotVIewedPreviousRewards()
    {
        return $this->where('view_status',self::VIEW_STATUS['PENDING'])->whereDate('created_at', '<', Carbon::today())->get();
    }
}
