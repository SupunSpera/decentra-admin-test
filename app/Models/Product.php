<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
    ];

    const PAYMENT_TYPE = [
        'ONE_TIME' => 0,
        'MONTHLY' => 1,
    ];

    const TOKEN_RATIO = 100;

    protected $fillable = [
        'name',
        'description',
        'payment_type',
        'price',
        'image_id',
        'status',
        'level',
        'points',
        'terms'
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
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection{
        return $this->where('status',self::STATUS['PUBLISH'])->get();
    }


}
