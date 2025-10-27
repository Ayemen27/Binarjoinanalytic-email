<?php

namespace App\Services;

class ImageService
{
    public function storeAvatar($avatar)
    {
        return store_file([
            'source' => $avatar,
            'path_to_save' => 'uploads/avatars/',
            'type' => "image",
        ]);
    }


    public function updateAvatar($avatar, $oldAvatar)
    {
        return store_file(
            [
                'source' => $avatar,
                'path_to_save' => 'uploads/avatars/',
                'type' => "image",
                'old_file' => $oldAvatar == config('lobage.default_avatar') ?  null : $oldAvatar,
            ]
        );
    }


    public function storePostImage($image)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => 'uploads/content/',
                'type' => "image",
                'resize' => [450, 250],
                'small_path' => 'small/',
                'new_extension' => "webp",
                'optimize' => true,
            ]
        );
    }



    public function updatePostImage($image, $oldImage, $oldSmallImage)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => 'uploads/content/',
                'type' => "image",
                'optimize' => true,
                'resize' => [450, 250],
                'small_path' => 'small/',
                'new_extension' => "webp",
                'old_file' => $oldImage,
                'old_small_file' => $oldSmallImage,
            ]
        );
    }



    public function storeSeoImage($image)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => 'uploads/seo/',
                'type' => "image",
                'optimize' => false,
            ]
        );
    }

    public function updateSeoImage($image, $oldImage)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => 'uploads/seo/',
                'type' => "image",
                'optimize' => false,
                'old_file' => $oldImage,
            ]
        );
    }


    public function updateThemeImage($image, $path_to_save, $name, $oldImage)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => $path_to_save,
                'type' => "image",
                'optimize' => false,
                'specific_name' => $name,
                'old_file' => $oldImage,
            ]
        );
    }


    public function storeProofImage($image)
    {
        return store_file(
            [
                'source' => $image,
                'path_to_save' => 'uploads/transactions/',
                'type' => "image",
                'optimize' => false,
            ]
        );
    }

    public function StoreSiteMap($file)
    {
        return store_file(
            [
                'source' => $file,
                'path_to_save' => public_path(),
                'type' => "file",
                'optimize' => false,
                'specific_name' => 'sitemap'
            ]
        );
    }



    public function storeImage($image)
    {
        return store_file([
            'source' => $image,
            'path_to_save' => 'uploads/editor/',
            'type' => "image",
        ]);
    }
}
