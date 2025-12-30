<?php

namespace domain\Services;

use App\Models\Milestone;
use Illuminate\Database\Eloquent\Collection;

use domain\Facades\ImageFacade;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class MilestoneService
{

    protected $milestone;

    public function __construct()
    {
        $this->milestone = new Milestone();
    }
    /**
     * Get milestone using id
     *
     * @param  int $id
     *
     * @return Milestone
     */
    public function get(int $id): Milestone
    {
        return $this->milestone->find($id);
    }

    /**
     * Get all milestone
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->milestone->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->milestone->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $milestone
     * @return Milestone
     */
    public function create(array $milestone): Milestone
    {
        return $this->milestone->create($milestone);
    }
    /**
     * Update milestone
     *
     * @param Milestone $milestone
     * @param array $data
     *
     *
     */
    public function update(Milestone $milestone, array $data)
    {
        return  $milestone->update($this->edit($milestone, $data));
    }
    /**
     * Edit milestone
     *
     * @param mMilestone $milestone
     * @param array $data
     *
     * @return array
     */
    protected function edit(Milestone $milestone, array $data): array
    {
        return array_merge($milestone->toArray(), $data);
    }
    /**
     * Delete a milestone
     *
     * @param Milestone $milestone
     *
     *
     */
    public function delete(Milestone $milestone)
    {
        return $milestone->delete();
    }

    /**
     * uploadImage
     *
     * @param  mixed $image
     * @return void
     */
    public function uploadImage($image)
    {

        $filename = Str::uuid()->toString() . time() . '.' . $image->getClientOriginalExtension();

        $img = ImageManager::imagick()->read($image);

        $img->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Optionally prevent upsizing
        });

        // Save image to disk
        if (!is_dir(storage_path('app/public/uploads/images/milestones/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/milestones/');
        }
        $img->save(storage_path('app/public/uploads/images/milestones/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }

    /**
     * getIdsExceptGiven
     *
     * @param  mixed $ids
     * @return void
     */
    function getIdsExceptGiven($ids){
       return $this->milestone->getIdsExceptGiven($ids);
    }
}
