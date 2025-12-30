<?php

namespace domain\Services;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

class ImageService
{

    protected $image;

    public function __construct()
    {
        $this->image = new Image();
    }

    /**
     * Create image object
     *
     * @param array $d
     *
     * @return Image
     */
    public function make(array $d): Image
    {
        //Set creating user_id
        $data['name'] = $d['name'];

        //Save object
        return $this->create($data);
    }

    /**
     * Save object to DB
     *
     * @param array $image
     *
     * @return Image
     */
    public function create(array $image): Image
    {
        return $this->image->create($image);
    }

    /**
     * Find image by id
     *
     * @param  int $id
     *
     * @return Image
     */
    public function find($id): Image
    {
        return $this->image->find($id);
    }
    /**
     * Find image by id
     *
     * @param int $image_id
     *
     * @return Image
     */
    public function getById(int $image_id): Image
    {
        //Find image data
        return $this->image->getById($image_id);
    }

    /**
     * Get all images
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        //Get all images
        return $this->image->all();
    }

    /**
     * Update Image
     *
     * @param Image $image
     * @param array $data
     *
     * @return void
     */
    public function update(Image $image, array $data)
    {
        //Update Image object with given data
        $this->image->update($this->edit($image, $data));
    }

    /**
     * Edit image
     *
     * @param Image $image
     * @param array $data
     *
     * @return array
     */
    protected function edit(Image $image, array $data)
    {
        return array_merge($image->toArray(), $data);
    }

    /**
     * Delete a image
     *
     * @param Image $image
     *
     * @return void
     */
    public function delete(Image $image): void
    {
        $image->delete();
    }

    /**
     * Get image name
     *
     * @param  int $id
     *
     * @return void
     */
    public function imageName(int $id)
    {
        $image_name = $this->image->get()->where('id', $id);
        foreach ($image_name as $value) {
            $name = $value->name;
        }
        return $name;
    }
}
