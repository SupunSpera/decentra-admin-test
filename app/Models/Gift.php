<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Gift extends Model
{
    use HasFactory;

    const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
    ];

    protected $fillable = [
        'name',
        'description',
        'token_amount',
        'image_id',
        'status',
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
