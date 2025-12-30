<?php

namespace domain\Services;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Collection;

class ProjectImageService
{

    protected $projectImage;

    public function __construct()
    {

        $this->projectImage = new ProjectImage();
    }
    /**
     * Get project using id
     *
     * @param  int $id
     *
     * @return ProjectImage
     */
    public function get(int $id): Project
    {
        return $this->projectImage->find($id);
    }

    /**
     * Get all project images
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->projectImage->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->projectImage->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $project
     * @return ProjectImage
     */
    public function create(array $projectImage): ProjectImage
    {
        return $this->projectImage->create($projectImage);
    }
    /**
     * Update projectImage
     *
     * @param ProjectImage $projectImage
     * @param array $data
     *
     *
     */
    public function update(Project $projectImage, array $data)
    {
        return  $projectImage->update($this->edit($projectImage, $data));
    }
    /**
     * Edit project image
     *
     * @param ProjectImage $projectImage
     * @param array $data
     *
     * @return array
     */
    protected function edit(ProjectImage $projectImage, array $data): array
    {
        return array_merge($projectImage->toArray(), $data);
    }
    /**
     * Delete a project image
     *
     * @param ProjectImage $projectImage
     *
     *
     */
    public function delete(ProjectImage $projectImage)
    {
        return $projectImage->delete();
    }


}
