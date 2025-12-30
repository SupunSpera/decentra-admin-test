<?php

namespace App\Http\Controllers;

use App\Traits\Api\ApiHelper;
use domain\Facades\ImageFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    use ApiHelper;
    /**
     * home
     *
     * @return void
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image'); //get image file from request

            $filename = Str::uuid()->toString() . time() . '.' . $image->getClientOriginalExtension();

            $img = ImageManager::imagick()->read($image);

            $img->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Optionally prevent upsizing
            });

            $folderName = "";
            if ($request['type'] == "institutes") {
                $folderName = "institutes";
            }
            // Save image to disk
            if (!is_dir(storage_path('app/public/uploads/images/' . $folderName . '/'))) {
                Storage::disk('public')->makeDirectory('/uploads/images/' . $folderName . '/');
            }
            $img->save(storage_path('app/public/uploads/images/' . $folderName . '/') . $filename);

            $imageData = array(
                'name' => $filename
            );
            $image = ImageFacade::make($imageData);
            if ($image) {
                $response['image_id'] = $image->id;
                return $this->successResponse($response, Response::HTTP_OK);
            } else {
                return $this->noDataResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }
}
