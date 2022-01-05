<?php
namespace App\Helpers;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class Create
{
    public static function fileUpload($requestFile, $fileName, $path, $folder)
    {
        $path = Storage::disk('public')->put(
            $path,
            $requestFile
        );

        $dataFile = [
            'name' => $fileName,
            'address' => Uuid::uuid4()->toString(),
            'path' => $path,
            'folder' => $folder,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];

        $file = File::create($dataFile);

        return $file;
    }
}
