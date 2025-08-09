<?php

namespace Modules\Core\Traits;

use Intervention\Image\Laravel\Facades\Image;

trait ImageTrait
{
    /**
     * Upload or update an image in Spatie Media Library with optional resizing if it exceeds the max file size.
     *
     * @param mixed  $model         The model instance that implements HasMedia
     * @param mixed  $image         UploadedFile instance of the image
     * @param string $collection    The media collection name
     * @param string $disk          Storage disk (default: public)
     * @param bool   $replaceOld    Whether to delete old media in the collection before uploading
     */
    public function uploadOrUpdateImageWithResize(
        $model,
        $image,
        string $collection,
        string $disk = 'private_media',
        bool $replaceOld = false
    ) {
        // If no image is provided, do nothing
        if (!$image) {
            return;
        }

        // Delete old image(s) if replaceOld is true
        if ($replaceOld) {
            $model->clearMediaCollection($collection);
        }

        // If file size exceeds the limit, resize
        if ($image->getSize() > 2048 * 1024) {
            // Resize and compress
            $resizedImage = Image::make($image)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio(); // Keep aspect ratio
                    $constraint->upsize();      // Prevent enlarging
                })
                ->encode('jpg', 75);

            // Save resized image temporarily
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            file_put_contents($tempPath, (string) $resizedImage);

            // Add resized image to media library
            $model->addMedia($tempPath)
                ->usingFileName(basename($tempPath))
                ->toMediaCollection($collection, $disk);

            // Delete temp file
            unlink($tempPath);
        } else {
            // Upload original image directly
            $model->addMedia($image)
                ->toMediaCollection($collection, $disk);
        }
    }
}
