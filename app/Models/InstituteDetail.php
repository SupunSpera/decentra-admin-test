<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstituteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'institute_id',
        'name',
        'address',
        'country',
        'image_id',
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
     * image
     *
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

      /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return InstituteDetail
     */
    public function getByCustomerId($id): ?InstituteDetail
    {
        return $this->where('customer_id', $id)->first();
    }
}
