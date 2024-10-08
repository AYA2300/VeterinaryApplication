<?php
namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

 trait FileStorageTrait{


    public function storeFile($file,string $folderName)
    {
        // $file = $request->file;
        // $originalName = $file->getClientOriginalName();

        // // Check for double extensions in the file name
        // if (preg_match('/\.[^.]+\./', $originalName)) {
        //     throw new Exception(trans('general.notAllowedAction'), 403);
        // }

        // //validate the mime type and extentions
        // $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jfif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        // $allowedExtensions = ['jpeg', 'png', 'gif', 'jpg', 'jfif', 'pdf', 'doc', 'docx'];

        // $mime_type = $file->getClientMimeType();
        // $extension = $file->getClientOriginalExtension();
        // //validate the mime type and extentions
        //  $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jfif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        // $allowedExtensions = ['jpeg', 'png', 'gif', 'jpg', 'jfif', 'pdf', 'doc', 'docx'];

        // $mime_type = $file->getClientMimeType();
        $extension = $file->getClientOriginalExtension();

        // if (!in_array($mime_type,$allowedMimeTypes) || !in_array($extension,$allowedExtensions)){
        //     throw new Exception(trans('general.invalidFileType'), 403);
        // }

        // Sanitize the file name to prevent path traversal
        $fileName = Str::random(32);
        $fileName = preg_replace('/[^A-Za-z0-9_\-]/','',$fileName);

        //store the file in the public disc
        $path = $file->storeAs($folderName,$fileName . '.' . $extension,'public');

        //verify the path to ensure it matches the expected pattern
        $expectedPath = storage_path('app/public/'. $folderName .'/' . $fileName . '.' . $extension);
        $actualPath = storage_path('app/public/'.$path);


        // get the url of the stored file
        // $url = Storage::disk('public')->url($path);
        $url = Storage::url($path);
        return $url;


    }
    // public function store_multi_images($images) :array
    // {
    //     $images=[];
    //     foreach ($images as $image) {
    //          $images[] = $this->storeFile($image, 'images');
    //     }
    //     return $images;

    // }

 }















?>
