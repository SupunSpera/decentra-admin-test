<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FrozenToken extends Model
{
    use HasFactory;

    const STATUS = [
        'FROZEN' => 0,
        'UNFREEZE' => 1

    ];

    protected $fillable = [
        'wallet_id',
        'token_amount',
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

    /**
     * getByWalletAndStatus
     *
     * @param  mixed $id
     * @return FrozenToken
     */
    public function getByWalletAndStatus($id): ?FrozenToken
    {
        return $this->where('wallet_id', $id)->where('status', self::STATUS['FROZEN'])->first();
    }
}
