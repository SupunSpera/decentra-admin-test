<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Item extends Model
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



    protected $fillable = [
        'name',
        'description',
        'payment_type',
        'price',
        'image_id',
        'status',
        'level',
        'points',
        'connected_project_id'
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

    /**
     * connectedProject
     *
     * @return void
     */
    public function connectedProject()
    {
        return $this->belongsTo(ConnectedProject::class, 'connected_project_id');
    }



}
