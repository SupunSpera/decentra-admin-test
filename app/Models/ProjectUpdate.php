<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class ProjectUpdate extends Model
{
    use HasFactory;

    const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
    ];

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'deliver_date',
        'status'
    ];

    /**
     * customer
     *
     * @return HasOne
     */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }


    /**
     * get project update images which belongs to project project update
     *
     * @return HasMany
     */
    public function projectUpdateImages(): HasMany
    {
        return $this->hasMany(ProjectUpdateImage::class, 'project_update_id', 'id');
    }


    /**
     * getByProject
     *
     * @param  mixed $project_id
     * @return Collection
     */
    public function getByProject($project_id): ? Collection
    {
        return $this->where('project_id', $project_id)->get();
    }
}
