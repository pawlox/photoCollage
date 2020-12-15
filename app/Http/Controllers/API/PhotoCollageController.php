<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoCollageResource;
use App\Models\PhotoCollage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotoCollageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $photoCollages = Auth::user()->photoCollages;

        return PhotoCollageResource::collection($photoCollages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PhotoCollageResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_width' => 'required|integer',
            'image_height' => 'required|integer',
            'photos' => 'array|size:4'
        ]);

        $imageWidth = $request->input('image_width');
        $imageHeight = $request->input('image_height');

        if ($request->has('photos')) {
            $collagePhotos = $this->generateFilesFromUpload($request->file('photos'), $imageWidth, $imageHeight);
        } else {
            $collagePhotos = $this->generateRandomFiles($imageWidth, $imageHeight);
        }

        $collagePath = $this->generatePhotoCollage($collagePhotos, $imageWidth, $imageHeight);

        $photoCollage = PhotoCollage::create([
            'path' => $collagePath,
            'user_id' => Auth::user()->id,
        ]);

        return new PhotoCollageResource($photoCollage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return PhotoCollageResource
     */
    public function show($id)
    {
        $photoCollage = PhotoCollage::where([
            'id' => $id,
            'user_id' => Auth::user()->id,
        ])->firstOrFail();

        return new PhotoCollageResource($photoCollage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return PhotoCollageResource
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image_width' => 'required|integer',
            'image_height' => 'required|integer',
            'photos' => 'array|size:4'
        ]);

        $photoCollage = PhotoCollage::where([
            'id' => $id,
            'user_id' => Auth::user()->id,
        ])->firstOrFail();

        $imageWidth = $request->input('image_width');
        $imageHeight = $request->input('image_height');

        if ($request->has('photos')) {
            $collagePhotos = $this->generateFilesFromUpload($request->file('photos'), $imageWidth, $imageHeight);
        } else {
            $collagePhotos = $this->generateRandomFiles($imageWidth, $imageHeight);
        }

        $collagePath = $this->generatePhotoCollage($collagePhotos, $imageWidth, $imageHeight);

        Storage::disk('public')->delete($photoCollage->path);

        $photoCollage->update([
            'path' => $collagePath,
        ]);

        return new PhotoCollageResource($photoCollage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photoCollage = PhotoCollage::where([
            'id' => $id,
            'user_id' => Auth::user()->id,
        ])->firstOrFail();

        Storage::disk('public')->delete($photoCollage->path);

        $photoCollage->delete();

        return response()->noContent();
    }

    /**
     * Generate resized photos array from uploaded files
     *
     * @param array $uploadedFiles
     * @param int $imageWidth
     * @param int $imageHeight
     * @return array
     */
    protected function generateFilesFromUpload(array $uploadedFiles, int $imageWidth, int $imageHeight): array
    {
        $resizedFiles = [];

        foreach ($uploadedFiles as $uploadedPhoto) {
            $tmpImage = Image::make($uploadedPhoto);
            $tmpImage->resize($imageWidth, $imageHeight);
            $tmpImage->save();

            $resizedFiles[] = $tmpImage;
        }

        return $resizedFiles;
    }

    /**
     * Generate random photos array
     *
     * @param int $imageWidth
     * @param int $imageHeight
     * @return array
     */
    protected function generateRandomFiles(int $imageWidth, int $imageHeight): array
    {
        $randomFiles = [];

        for($i = 0; $i < 4; $i++) {
            $tmpFilePath = 'tmp/' . uniqid('', true) . '.jpg';

            Storage::put($tmpFilePath, file_get_contents("https://picsum.photos/{$imageWidth}/{$imageHeight}"));

            $randomFiles[] = Storage::path($tmpFilePath);
        }

        return $randomFiles;
    }

    /**
     * Genearte photo collage from provided array of photos
     *
     * @param array $collagePhotos
     * @param int $imageWidth
     * @param int $imageHeight
     * @return string
     */
    protected function generatePhotoCollage(array $collagePhotos, int $imageWidth, int $imageHeight): string
    {
        $img_canvas = Image::canvas($imageWidth * 2, $imageHeight * 2);
        $img_canvas->insert($collagePhotos[0], 'top-left');
        $img_canvas->insert($collagePhotos[1], 'top-right');
        $img_canvas->insert($collagePhotos[2], 'bottom-left');
        $img_canvas->insert($collagePhotos[3], 'bottom-right');

        $collagePath = 'collages/' . uniqid('', true) . '.jpg';

        $img_canvas->save(Storage::disk('public')->path($collagePath));

        return $collagePath;
    }
}
