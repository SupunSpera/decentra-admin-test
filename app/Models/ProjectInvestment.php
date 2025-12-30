<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInvestment extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'COMPLETED' => 1,
    ];

    protected $fillable = [
        'customer_id',
        'project_id',
        'amount',
        'points',
        'status',

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
     * project
     *
     * @return void
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    /**
     * getInvestmentTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getInvestmentTotalByDate($date)
    {
        return $this->whereDate('created_at', $date)
            ->sum('amount');
    }

    /**
     * getInvestedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getInvestedPointsTotalByDate($date){
        return $this->whereDate('created_at', $date)
        ->sum('points');
    }
}
