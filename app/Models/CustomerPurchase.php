<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomerPurchase extends Model
{
    use HasFactory;

    const STATUS = [
        'AVAILABLE' => 1,
        'UNAVAILABLE' => 0,
    ];

    const TYPE = [
        'PRODUCT' => 0,
        'ITEM' => 1,
        'THIRD_PARTY' => 2,
    ];

    const INCOME_QUOTA_STATUS = [
        'AVAILABLE' => 1,
        'EXPIRED' => 0,
    ];

    protected $fillable = [
        'item_id',
        'customer_id',
        'type',
        'amount',
        'points',
        'max_income_quota',
        'remaining_income_quota',
        'income_quota_status',
        'project_id',
        'status'
    ];

    /**
     * getTotalAvailableQuotaByCustomer
     *
     * @param  mixed $customer_id
     * @return Int
     */
    public function getTotalAvailableQuotaByCustomer($customer_id): Int
    {
        return $this->where('customer_id', $customer_id)
            ->where('income_quota_status', self::INCOME_QUOTA_STATUS['AVAILABLE'])->sum('max_income_quota');
    }

    /**
     * getPurchasedTotalByCustomerId
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function getPurchasedTotalByCustomerId($customer_id)
    {
        $total = $this->where('customer_id', $customer_id)
            ->sum('amount');

        return $total;
    }

    /**
     * getQuotaAvailableByCustomer
     *
     * @param  mixed $customer_id
     * @return Collection
     */
    public function getQuotaAvailableByCustomer($customer_id): ?Collection
    {
        return $this->where('customer_id', $customer_id)
            ->where('income_quota_status', self::INCOME_QUOTA_STATUS['AVAILABLE'])->get();
    }

    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date)
    {
        return $this->where('type', self::TYPE['THIRD_PARTY'])
            ->whereDate('created_at', $date)
            ->sum('points');
    }

    /**
     * getPurchaseTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchaseTotalByDate($date)
    {
        return $this->where('type', self::TYPE['THIRD_PARTY'])
            ->whereDate('created_at', $date)
            ->sum('amount');
    }
}
