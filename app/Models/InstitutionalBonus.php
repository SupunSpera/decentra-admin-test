<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class InstitutionalBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'transaction_id',
        'amount',
        'status'
    ];

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
     * customer
     *
     * @return HasOne
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(WalletTransaction::class, 'id', 'transaction_id');
    }

      /**
     * getTotalAmount
     *
     * @return void
     */
    public function getTotalAmount() {
        return $this->select(DB::raw('SUM(amount) as total'))
        ->get()
        ->first()
        ->total;
    }
}
