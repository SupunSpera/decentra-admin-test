<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductPurchase extends Model
{
    use HasFactory;


    const STATUS = [
        'AVAILABLE' => 1,
        'UNAVAILABLE' => 0,
    ];

    const INCOME_QUOTA_STATUS = [
        'AVAILABLE' => 1,
        'EXPIRED' => 0,
    ];

    protected $fillable = [
        'product_id',
        'customer_id',
        'max_income_quota',
        'remaining_income_quota',
        'income_quota_status',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    /**
     * getPurchaseTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchaseTotalByDate($date)
    {
        return $this->join('products', 'product_purchases.product_id', '=', 'products.id')
            ->whereDate('product_purchases.created_at', $date)
            ->sum('products.price');
    }


    /**
     * getPurchasedPointsTotalByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedPointsTotalByDate($date)
    {
        return $this->join('products', 'product_purchases.product_id', '=', 'products.id')
            ->whereDate('product_purchases.created_at', $date)
            ->sum('products.points');
    }

    /**
     * getPurchasedTotalByCustomers
     *
     * @param  mixed $customers
     * @param  mixed $date
     * @return void
     */
    public function getPurchasedTotalByCustomers($customers, $date)
    {

        return $this->join('products', 'product_purchases.product_id', '=', 'products.id')
            ->whereIn('product_purchases.customer_id', $customers)
            ->whereDate('product_purchases.created_at', '<=', $date)
            ->sum('products.price');
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
     * getProductPurchasedCustomersByIds
     *
     * @param  mixed $customerIds
     * @return void
     */
    function getProductPurchasedCustomersByIds($customerIds,$date)
    {
        return  $this->whereDate('product_purchases.created_at', '>=', $date)
        ->whereIn('product_purchases.customer_id', $customerIds)->pluck('customer_id');
    }

    /**
     * getProductPurchasedCustomersWithPeriod
     *
     * @param  mixed $date
     * @return void
     */
    function getProductPurchasedCustomersWithPeriod($date){
        return  $this->distinct()->whereDate('product_purchases.created_at', '>=', $date)
        ->pluck('customer_id');
    }

    /**
     * getPurchasedReferralsByDirectReferral
     *
     * @param  mixed $directReferral
     * @return void
     */
    function getPurchasedReferralsByDirectReferral($directReferral)
    {
        return $this->join('referrals', 'product_purchases.customer_id', '=', 'referrals.customer_id')
            ->select('referrals.customer_id')
            ->where('referrals.direct_referral_id', '=', $directReferral)
            ->pluck('customer_id');
    }

    /**
     * getPurchasedTotalByCustomerId
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function getPurchasedTotalByCustomerId($customer_id)
    {
        $total = $this->join('products', 'product_purchases.product_id', '=', 'products.id')
            ->where('product_purchases.customer_id', $customer_id)
            ->sum('products.price');

        return $total * 100;
    }

}
