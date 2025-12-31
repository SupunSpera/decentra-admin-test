<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Customer extends Authenticatable
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'ACTIVE' => 1
    ];

    const ACTIVE_STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1,
    ];

    const PURCHASED_STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1,
    ];

    const TYPE = [
        'INDIVIDUAL' => 1,
        'INSTITUTE' => 2
    ];

    const TWO_FA_METHODS = [
        "G_TWO_FA" => 1,
        "EMAIL_TWO_FA" => 2,
    ];

    const EMAIL_TWO_FACTOR = [
        "DEACTIVATED" => 0,
        "PENDING" => 1,
        "ACTIVATED" => 2,
    ];

    const EMAIL_TWO_FACTOR_SENT = [
        "NOT_YET" => 0,
        "SENT" => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'telephone',
        'mobile',
        'referral_code',
        'email',
        'introduction',
        'direct_ref_code',
        'type',
        'status',
        'active_status',
        'purchased_status',
        'password',
        'email_two_factory',
        'email_code',
        'email_sent',
        'email_code_sent_at',
        'frozen_shares',

    ];

    protected $hidden = [
        'password',
    ];

    /**
     * boot
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {

            // Ensure a positive starting number
            $customersCount = count(Customer::all());
            if ($customersCount > 0) {
                $referralCode = Customer::latest()->first()->referral_code;
                // Remove the non-numeric characters
                $numericPart = (int) preg_replace('/[^0-9]/', '', $referralCode);
                $startNumber = $numericPart + 1;

            } else {
                $startNumber = 10000;
            }


            // Pad the number with leading zeros to reach 6 digits
            $paddedNumber = str_pad($startNumber, 6, '0', STR_PAD_LEFT);

            // Combine the prefix "DX" with the padded number
            $txCode = "DX" . $paddedNumber;

            // Append a unique identifier to the slug
            $customer->referral_code = $txCode;
        });
    }

    /**
     * wallet
     *
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'customer_id', 'id');
    }

    /**
     * productPurchases
     *
     * @return HasMany
     */
    public function productPurchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class, 'customer_id', 'id');
    }


    /**
     * getByRefCode
     *
     * @param  mixed $code
     * @return void
     */
    public function getByRefCode($code)
    {
        return $this->where('referral_code', $code)->first();
    }

    /**
     * getByEmail
     *
     * @param  mixed $email
     * @return void
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }



    /**
     * getByTypeAndStatus
     *
     * @param  mixed $type
     * @param  mixed $status
     * @return void
     */
    public function getByTypeAndStatus($type, $status)
    {
        return $this->where('type', $type)->where('status', $status)->get();
    }
    /**
     * getByTypeAndStatus
     *
     * @param  mixed $type
     * @param  mixed $status
     * @return void
     */
    public function getByCustomerTypeAndStatus($customerId, $type, $status)
    {
        return $this->where('type', $type)->where('id', $customerId)->where('status', $status)->get();
    }
    /**
     * getNfcCardNotActivatedCustomers
     *
     * @param  mixed $activatedCustomerIds
     * @return Collection
     */
    public function  getNfcCardNotActivatedCustomers($activatedCustomerIds): ?Collection
    {
        return $this->where('type', self::TYPE['INDIVIDUAL'])->where('status', self::STATUS['ACTIVE'])->whereNotIn('id', $activatedCustomerIds)->get();
    }

    /**
     * getCustomerIdsExceptGiven
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getCustomerIdsExceptGiven($customerIds)
    {

        return $this->whereNotIn('id', $customerIds)
            ->pluck('id');
    }
}
