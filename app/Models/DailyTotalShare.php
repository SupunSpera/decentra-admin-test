<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTotalShare extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'CONVERTED' => 1
    ];

    protected $fillable = [
        'date',
        'value',
        'status',
        'customer_id',
    ];



    /**
     * getByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByDate($date){
        return $this->where('date', $date)
        ->where('status',self::STATUS['PENDING'])
        ->get();
    }

    /**
     * getByTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getByTotalByDate($date){
        return $this->where('date', $date)
        ->where('status',self::STATUS['PENDING'])
        ->sum('value');
    }
}
