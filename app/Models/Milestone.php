<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Milestone extends Model
{
    use HasFactory;

    const TYPE = [
        'DIRECT_REFERRAL' => 0,
        'CLIENT_BASE' => 1,
    ];
    const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
    ];

    protected $fillable = [
        'name',
        'level',
        'count',
        'type',
        'image_id',
        'status'
    ];


      /**
     * images
     *
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

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
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->where('status', self::STATUS['PUBLISHED'])->orderBy('level')->get();
    }

       /**
     * getCustomerIdsExceptGiven
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getIdsExceptGiven($ids){

        return $this->whereNotIn('id', $ids)
        ->where('status', self::STATUS['PUBLISHED'])
        ->get();
        // ->pluck('id');
    }

}
