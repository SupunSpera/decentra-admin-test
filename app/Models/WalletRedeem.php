<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletRedeem extends Model
{
    use HasFactory;

    const STATUS = [
        'MEMBER_APPROVAL_PENDING' => 0,
        'PENDING' => 1,
        'APPROVED' => 2,
        'WITHDRAWAL_PENDING' => 3,
        'SENT' => 4,
        'REJECTED' => 5
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'wallet_address',
        'wallet_transaction_id',
        'withdrawal_fee_transaction_id',
        'admin_fee_transaction_id',
        'amount',
        'status'
    ];


    /**
     * getByWalletAddress
     *
     * @param  mixed $address
     * @return WalletRedeem
     */
    public function getByWalletAddress($address) : ?WalletRedeem
    {
        return $this->where('wallet_address', $address)->first();
    }
}
