<?php

namespace domain\Services;

use App\Models\ProjectUpdate;
use domain\Facades\ImageFacade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class ProjectUpdateService
{

    protected $projectUpdate;

    public function __construct()
    {
        $this->projectUpdate = new ProjectUpdate();
    }

    /**
     * Get projectUpdate using id
     *
     * @param  int $id
     *
     * @return ProjectUpdate
     */
    public function get(int $id): ProjectUpdate
    {
        return $this->projectUpdate->find($id);
    }

    /**
     * Get all projectUpdates
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->projectUpdate->all();
    }
    /**
     * create
     *
     * @param  mixed $projectUpdate
     * @return ProjectUpdate
     */
    public function create(array $projectUpdate): ProjectUpdate
    {
        return $this->projectUpdate->create($projectUpdate);
    }


    /**
     * update
     *
     * @param  mixed $projectUpdate
     * @param  mixed $data
     * @return void
     */
    public function update(ProjectUpdate $projectUpdate, array $data)
    {
       return $projectUpdate->update($this->edit($projectUpdate, $data));
    }

    /**
     * Edit projectUpdate
     *
     * @param ProjectUpdate $projectUpdate
     * @param array $data
     *
     * @return array
     */
    protected function edit(ProjectUpdate $projectUpdate, array $data): array
    {
        return array_merge($projectUpdate->toArray(), $data);
    }

    /**
     * Delete a projectUpdate
     *
     * @param ProjectUpdate $projectUpdate
     *
     * @return void
     */
    public function delete(ProjectUpdate $projectUpdate)
    {
      return  $projectUpdate->delete();
    }


    /**
     * getByProject
     *
     * @param  mixed $customer_id
     * @return Collection
     */
    public function getByProject(int $project_id): Collection
    {
        return $this->projectUpdate->getByProject($project_id);
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
        if (!is_dir(storage_path('app/public/uploads/images/projects/updates/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/projects/updates/');
        }
        $img->save(storage_path('app/public/uploads/images/projects/updates/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }

}
