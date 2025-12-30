<?php

namespace domain\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

use domain\Facades\ImageFacade;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ProductService
{

    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }
    /**
     * Get product using id
     *
     * @param  int $id
     *
     * @return Product
     */
    public function get(int $id): Product
    {
        return $this->product->find($id);
    }

    /**
     * Get all products
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->product->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->product->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $product
     * @return Product
     */
    public function create(array $product): Product
    {
        return $this->product->create($product);
    }
    /**
     * Update product
     *
     * @param Product $product
     * @param array $data
     *
     *
     */
    public function update(Product $product, array $data)
    {
        return  $product->update($this->edit($product, $data));
    }
    /**
     * Edit product
     *
     * @param Product $product
     * @param array $data
     *
     * @return array
     */
    protected function edit(Product $product, array $data): array
    {
        return array_merge($product->toArray(), $data);
    }
    /**
     * Delete a product
     *
     * @param Product $product
     *
     *
     */
    public function delete(Product $product)
    {
        return $product->delete();
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
}
