<?php

namespace domain\Services;

use App\Models\Item;

use Illuminate\Support\Str;
use domain\Facades\ImageFacade;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class ItemService
{

    protected $item;

    public function __construct()
    {
        $this->item = new Item();
    }
    /**
     * Get item using id
     *
     * @param  int $id
     *
     * @return Item
     */
    public function get(int $id): Item
    {
        return $this->item->find($id);
    }

    /**
     * Get all products
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->item->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->item->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $item
     * @return Item
     */
    public function create(array $item): Item
    {
        return $this->item->create($item);
    }
    /**
     * Update item
     *
     * @param Item $item
     * @param array $data
     *
     *
     */
    public function update(Item $item, array $data)
    {
        return  $item->update($this->edit($item, $data));
    }
    /**
     * Edit item
     *
     * @param Item $item
     * @param array $data
     *
     * @return array
     */
    protected function edit(Item $item, array $data): array
    {
        return array_merge($item->toArray(), $data);
    }
    /**
     * Delete a item
     *
     * @param Item $item
     *
     *
     */
    public function delete(Item $item)
    {
        return $item->delete();
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
        if (!is_dir(storage_path('app/public/uploads/images/products/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/products/');
        }
        $img->save(storage_path('app/public/uploads/images/products/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }

    /**
     * uploadImageImages
     *
     * @param  mixed $image
     * @return void
     */
    public function uploadImageImages($image)
    {

        $filename = Str::uuid()->toString() . time() . '.' . $image->getClientOriginalExtension();

        $img = ImageManager::imagick()->read($image);

        $img->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Optionally prevent upsizing
        });

        // Save image to disk
        if (!is_dir(storage_path('app/public/uploads/images/items/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/items/');
        }
        $img->save(storage_path('app/public/uploads/images/items/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }
}
