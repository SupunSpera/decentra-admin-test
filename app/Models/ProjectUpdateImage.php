<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Project  Update Image
 *
 * php version 8
 *
 * @category Model
 * @author   Spera Labs
 * @license  https://decentrax.com Config
 * @link     https://decentrax.com/
 *
 * */

class ProjectUpdateImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_id',
        'project_id',
        'project_update_id',
    ];

    /**
     * get images which belongs project
     *
     * @return BelongsTo
     */
    public function images(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }
    /**
     * get project which belongs album documents
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
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
}
