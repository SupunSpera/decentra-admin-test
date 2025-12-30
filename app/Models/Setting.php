<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_income_cap',
        'share_value',
        'usd_to_bte_fee',
        'bte_to_usd_fee',
        'withdrawal_fee',
        'unhold_date',
        'unhold_period',
        'unfreeze_date',
        'unfreeze_period'
    ];

     /**
     * getFirstRecord
     *
     * @return void
     */
    public function getFirstRecord() {
        return $this->get()
        ->first();
    }
}
