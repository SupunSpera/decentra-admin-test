<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WalletTransaction extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'SUCCESS' => 1,
        'CANCELLED' => 2,
    ];
    const FROM = [
        'CUSTOMER' => 0,
        'ADMIN' => 1,
    ];


    const TYPE = [
        'DEPOSIT' => 1,
        'PURCHASE' => 2,
        'SUPPORTING_BONUS' => 3,
        'WITHDRAW' => 4,
        'SWAP' => 5,
        'DIRECT_REFERRAL_BONUS' => 6,
        'WITHDRAW_FEE' => 7,
        'SWAP_FEE' => 8,
        'ADMIN_FEE' => 9,
        'PROJECT_HARVEST' => 10,
        'PROJECT_DIRECT_COMMISSION' => 11,
        'URBX_WITHDRAWAL' => 12,
        'ITEM_DIRECT_COMMISSION' => 13
    ];

    protected $fillable = [
        'wallet_id',
        'token_amount',
        'usdt_amount',
        'type',
        'from',
        'tx_hash',
        'status',
        'isLocked',
        'network',
        'confirmed',
        'collected'
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
