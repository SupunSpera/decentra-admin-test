<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WalletDeposit extends Model
{
        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_deposits';

    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'eth_wallet_address',
    ];

       /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return WalletDeposit
     */
    public function getByCustomerId($id) : ?WalletDeposit
    {
        return $this->where('customer_id', $id)->first();
    }

    /**
     * getExpiredDeposits
     *
     * @return Collection
     */
    public function getExpiredDeposits() :Collection
    {
       return $this->where('created_at', '<', Carbon::now()->subMinutes(10))->get();
    }
}
