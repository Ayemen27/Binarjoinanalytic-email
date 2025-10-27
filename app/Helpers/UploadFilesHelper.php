<?php

//use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


function store_file($options)
{
    $small_name = null;

    $options = array_merge([
        'source' => "",
        'type' => "file",
        'path_to_save' => 'uploads/files/',
        'old_file' => NULL,
        'old_small_file' => NULL,
        'specific_name' => NULL,
        'resize' => [900, 400],
        'optimize' => false,
        'small_path' => 'small/',
        'new_extension' => "",
    ], $options);

    $file           = $options["source"];
    $old            = $options["old_file"];
    $old_small      = $options["old_small_file"];
    $location       = $options["path_to_save"];
    $path_small     = $options["path_to_save"] . $options["small_path"]; // '/uploads/files/small/';


    empty($location) ? public_path() : makeDirectory($location);



    //makeDirectory($location);
    if (!empty($old)) {
        removeFile($old);
        if (!empty($old_small)) {
            removeFile($old_small);
        }
    }

    if ($options["specific_name"] != null) {
        $filename = $options["specific_name"];
    } else {
        $filename = Str::random(15) . '_' . time();
    }

    $name = $filename . '.' . $file->extension();

    $file->move($location, $name);


    if ($options["type"] == 'image' && $options["optimize"]) {

        $extension = $options['new_extension'] == "" ? $file->extension() : $options['new_extension'];
        $small_name = $path_small . $filename . '.' . $extension;

        makeDirectory($path_small);

        $image = Image::read($location . $name);

        $image->resize($options["resize"][0], $options["resize"][1]);
        $image->save($small_name);
    }

    return [
        'success'  => true,
        'filename' => $location . $name,
        'small_filename' => $small_name,
    ];
}


function removeFile($path)
{
    if (!file_exists($path)) {
        return true;
    }
    return File::delete($path);
}

function removeFileOrFolder($path)
{
    if (!file_exists($path)) {
        return true; // The file or folder doesn't exist, so consider it removed.
    }

    if (is_dir($path)) {
        // Recursively delete the directory and its contents
        return File::deleteDirectory($path);
    }

    // If it's a file, delete it
    return File::delete($path);
}

function makeDirectory($path)
{
    if (File::exists($path)) {
        return true;
    }
    return File::makeDirectory($path, 0775, true);
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}


function AvatarFromUrl(string $url)
{
    try {
        // Get the image content from the URL
        $imageContent = file_get_contents($url);
        if ($imageContent === false) {
            //MyLog($url);
            return config('lobage.default_avatar');
        }

        // Define the path to save the image
        $filePath = 'uploads/avatars/' . Str::random(15) . '_' . time() . ".jpg";

        // Ensure the directory exists
        if (!File::isDirectory(public_path('uploads/avatars'))) {
            File::makeDirectory(public_path('uploads/avatars'), 0755, true);
        }

        // Save the image to the storage
        File::put($filePath, $imageContent);

        //MyLog($url, ['image' => $filePath]);

        return $filePath;
    } catch (Exception $e) {
        //MyLog('Error saving avatar', ['exception' => $e->getMessage()]);
        return config('lobage.default_avatar');
    }
}
