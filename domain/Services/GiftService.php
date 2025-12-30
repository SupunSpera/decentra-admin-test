<?php

namespace domain\Services;

use App\Models\Gift;
use Illuminate\Database\Eloquent\Collection;

use domain\Facades\ImageFacade;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class GiftService
{

    protected $gift;

    public function __construct()
    {
        $this->gift = new Gift();
    }
    /**
     * Get gift using id
     *
     * @param  int $id
     *
     * @return Gift
     */
    public function get(int $id): Gift
    {
        return $this->gift->find($id);
    }

    /**
     * Get all gifts
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->gift->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->gift->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $gift
     * @return Gift
     */
    public function create(array $gift): Gift
    {
        return $this->gift->create($gift);
    }
    /**
     * Update gift
     *
     * @param Gift $gift
     * @param array $data
     *
     *
     */
    public function update(Gift $gift, array $data)
    {
        return  $gift->update($this->edit($gift, $data));
    }
    /**
     * Edit gift
     *
     * @param Gift $gift
     * @param array $data
     *
     * @return array
     */
    protected function edit(Gift $gift, array $data): array
    {
        return array_merge($gift->toArray(), $data);
    }
    /**
     * Delete a gift
     *
     * @param Gift $gift
     *
     *
     */
    public function delete(Gift $gift)
    {
        return $gift->delete();
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
        if (!is_dir(storage_path('app/public/uploads/images/gifts/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/gifts/');
        }
        $img->save(storage_path('app/public/uploads/images/gifts/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }
}
