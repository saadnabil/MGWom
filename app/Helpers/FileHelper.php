<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
      /**
     * @param $folder_path
     * @param UploadedFile $file
     * @return null
     */
    public static function upload_file($folder_path, $file)
    {
        if (!$file) return null;
        $file_path = $file->store($folder_path);
        return $file_path;
    }
    /**
     * @param string $folder_path
     * @param UploadedFile $file
     * @param string $old_file
     * @return null
     */
    public static function update_file($folder_path, $file, $old_file)
    {
        if (!$file) return null;

        if (!empty($old_file)) {
            Storage::delete($old_file);
        }
        return $file->store($folder_path);
    }

    public static function delete_files(array $filesToDelete){
        foreach ($filesToDelete as $file) {
            if($file == null) return;
                Storage::delete($file);
            return true;
        }
        return true;
    }

    public static function delete_file($file)
    {
        if($file == null) return;
        Storage::delete($file);
        return true;
    }

    public static function deleteDirectory($path)
    {
        if ($path != null) {
            Storage::deleteDirectory($path);
        }
    }

    private static function getImageName($file_path){
        $arr = explode('/', $file_path);
        return $arr[count($arr) - 1];
    }
}
