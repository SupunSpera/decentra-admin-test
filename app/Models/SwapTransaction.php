<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SwapTransaction extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'SUCCESS' => 1,
        'CANCELLED' => 2,
    ];

    const TYPE = [
        'USD_TO_TEX' => 1,
        'TEX_TO_USDT' => 2
    ];

    protected $fillable = [
        'wallet_id',
        'token_amount',
        'usdt_amount',
        'type',
        'status'
    ];


    /**
     * wallet
     *
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }

}
