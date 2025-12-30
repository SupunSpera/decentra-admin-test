<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class DailyShareCalculation extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'ACTIVE' => 1
    ];


    const TYPE = [
        'POSITIVE' => 0,
        'NEGATIVE' => 1
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_sales',
        'total_point',
        'point_value_bte',
        'binary_pool_bte',
        'qualified_shares',
        'real_share_value',
        'system_share_value',
        'payout',
        'pool_balance',
        'cumulative_pool_balance',
        'status',
        'type'
    ];


    /**
     * getByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByDate($date){
        return $this->where('date', $date)
        ->get();
    }

    /**
     * getLastRecord
     *
     * @return void
     */
    public function getLastRecord(){
       return DailyShareCalculation::latest('id')->first();
    }

}
