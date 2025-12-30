<?php

namespace App\Models\Gift;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NfcCustomer extends Model
{
    use HasFactory;

    const CARD_STATUS = [
        'PENDING' => 0,
        'SENT' => 1,
        'DELIVERED' => 2,
    ];

    const PURCHASE_STATUS = [
        'NO' => 0,
        'YES' => 1
    ];

    const STATUS = [
        'PENDING' => 0,
        'ACTIVE' => 1
    ];

    protected $fillable = [
        'customer_id',
        'card_status',
        'purchase_status',
        'nfc_connected_id',
        'status',
        'simulate_session',

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
     * getNfcActivatedCustomersIds
     *
     * @return void
     */
    public function getNfcActivatedCustomersIds()
    {
        return $this->pluck('customer_id')->toArray();
    }


    /**
     * getBySessionHash
     *
     * @param  mixed $hash
     * @return Array
     */
    public function getBySessionHash($hash): ?array
    {
        $data =  $this->where('simulate_session', $hash)
            ->first();

        if ($data) {
            $data->update(['simulate_session' => null]); // destroy session hash
            return array(
                'customer_id' => $data->nfc_connected_id
            );
        } else {
            return array(
                'customer_id' => null
            );
        }
    }
}
