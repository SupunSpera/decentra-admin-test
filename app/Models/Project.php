<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
        'STARTED' => 2,
        'COMPLETED' => 3,
    ];

    const TYPE = [
        'SHORT_TERM' => 0,
        'LONG_TERM' => 1,
    ];

    const DURATION_TYPE = [
        'MONTHS' => 0,
        'YEARS' => 1,
    ];

    const HARVEST_TYPE = [
        'MONTHLY' => 0,
        'ON_COMPLETE' => 1,
    ];

    const BONUS_GENERATION = [
        'EARLY' => 0,
        'ON_COMPLETE' => 1,
    ];


    protected $fillable = [
        'name',
        'description',
        'total_value',
        'invested_amount',
        'minimum_investment',
        'status',
        'image_id',
        'type',
        'duration',
        'duration_type',
        'harvest',
        'direct_commission',
        'points',
        'harvest_type',
        'bonus_generation',
        'started_date',
        'end_date',
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
    public function getPublished(): Collection
    {
        return $this->where('status', self::STATUS['PUBLISHED'])->get();
    }

    /**
     * get project images which belongs to project
     *
     * @return HasMany
     */
    public function projectImages(): HasMany
    {
        return $this->hasMany(ProjectImage::class, 'project_id', 'id');
    }

    /**
     * projectInvestments
     *
     * @return HasMany
     */
    public function projectInvestments(): HasMany
    {
        return $this->hasMany(ProjectInvestment::class, 'project_id', 'id');
    }


    /**
     * getStartedProjectsWithDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getStartedProjectsWithDate($date)
    {
        return Project::where('status', self::STATUS['STARTED'])
            ->where('end_date', $date)
            ->get();
    }
}
