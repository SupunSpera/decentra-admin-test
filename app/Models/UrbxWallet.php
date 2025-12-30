<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class UrbxWallet extends Model
{
    use HasFactory;

    const URBX_VALUE = 100;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'token_amount',
        'usdt_amount',
        'metamask_wallet_address',
        'status',
        'withdrawal_address',
        'row_key'

    ];


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::updating(function ($wallet) {
    //         $changes = $wallet->getDirty(); // Get only changed attributes

    //         self::logWalletUpdate($wallet, [
    //             'token_amount' => $changes['token_amount'] ?? $wallet->token_amount,
    //             'usdt_amount' => $changes['usdt_amount'] ?? $wallet->usdt_amount,
    //             'holding_tokens' => $changes['holding_tokens'] ?? $wallet->holding_tokens,
    //             'holding_usdt' => $changes['holding_usdt'] ?? $wallet->holding_usdt,
    //         ]);
    //     });
    // }

    // private static function logWalletUpdate($wallet, $data)
    // {
    //     DB::table('wallet_logs')->insert([
    //         'wallet_id' => $wallet->id,
    //         'token_amount' => $data['token_amount'] ?? 0,
    //         'usdt_amount' => $data['usdt_amount'] ?? 0,
    //         'holding_tokens' => $data['holding_tokens'] ?? 0,
    //         'holding_usdt' => $data['holding_usdt'] ?? 0,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    // }

      /**
     * customer
     *
     * @return HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

      /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->where('customer_id', $id)->first();
    }


    // /**
    //  * getByWalletAddress
    //  *
    //  * @param  mixed $address
    //  * @return void
    //  */
    // public function getByWalletAddress($address)
    // {
    //     return $this->where('eth_wallet_address', $address)->first();
    // }
}
