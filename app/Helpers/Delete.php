<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Delete
{
    public static function storageFile($table)
    {
        $deleteFile = Storage::disk('public')->delete($table->file->path);

        $table->file->delete();
        
        return true;
    }
}
