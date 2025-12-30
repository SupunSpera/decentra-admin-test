<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TokenValue extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token_value',
    ];


    /**
     * getTokenValueDates
     *
     * @return void
     */
    public function getTokenValueDates()
    {
        return $this->select(DB::raw('DATE(created_at) AS unique_date'))
        ->distinct()
        ->get();
    }

    /**
     * getMinAndMaxTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getMinAndMaxTokenValueOfDate($date)
    {
        return $this->select(DB::raw('MIN(token_value) AS min_value'), DB::raw('MAX(token_value) AS max_value'))
        ->whereDate('created_at', '=', $date)
        ->get();
    }

     /**
     * getMinAndMaxTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getStartingTokenValueOfDate($date)
    {
        return $this->whereDate('created_at', '=',$date)
        ->orderBy('token_value', 'asc')
        ->first()->token_value;
    }

    /**
     * getMinAndMaxTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getClosingTokenValueOfDate($date)
    {
        return $this->whereDate('created_at', '=',$date)
        ->orderBy('token_value', 'decs')
        ->first()->token_value;
    }

}
