<?php

namespace domain\Services;

use App\Models\Project;
use App\Models\ProjectUpdateImage;
use Illuminate\Database\Eloquent\Collection;

class ProjectUpdateImageService
{

    protected $projectUpdateImage;

    public function __construct()
    {

        $this->projectUpdateImage = new projectUpdateImage();
    }
    /**
     * Get projectUpdateImage using id
     *
     * @param  int $id
     *
     * @return projectUpdateImage
     */
    public function get(int $id): projectUpdateImage
    {
        return $this->projectUpdateImage->find($id);
    }

    /**
     * Get all projectUpdateImage
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->projectUpdateImage->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->projectUpdateImage->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $projectUpdateImage
     * @return projectUpdateImage
     */
    public function create(array $projectUpdateImage): projectUpdateImage
    {
        return $this->projectUpdateImage->create($projectUpdateImage);
    }
    /**
     * Update projectUpdateImage
     *
     * @param projectUpdateImage $projectUpdateImage
     * @param array $data
     *
     *
     */
    public function update(projectUpdateImage $projectUpdateImage, array $data)
    {
        return  $projectUpdateImage->update($this->edit($projectUpdateImage, $data));
    }
    /**
     * Edit projectUpdateImage
     *
     * @param projectUpdateImage $projectUpdateImage
     * @param array $data
     *
     * @return array
     */
    protected function edit(projectUpdateImage $projectUpdateImage, array $data): array
    {
        return array_merge($projectUpdateImage->toArray(), $data);
    }
    /**
     * Delete a project update image
     *
     * @param projectUpdateImage $projectUpdateImage
     *
     *
     */
    public function delete(Project $projectUpdateImage)
    {
        return $projectUpdateImage->delete();
    }
}
