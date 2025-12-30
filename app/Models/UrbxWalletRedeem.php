<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrbxWalletRedeem extends Model
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
        'urbx_wallet_id',
        'metamask_wallet_address',
        'amount',
        'status',
        'wallet_transaction_id'
    ];


    /**
     * getByWalletAddress
     *
     * @param  mixed $address
     * @return UrbxWalletRedeem
     */
    public function getByWalletAddress($address) : ?UrbxWalletRedeem
    {
        return $this->where('metamask_wallet_address', $address)->first();
    }
}
