<?php

namespace Modules\Core\Traits;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageTrait
{
    public function uploadOrUpdateImageWithResize(
        $model,
        $images,
        string $collection,
        string $disk = 'private_media',
        bool $replaceOld = false
    ) {
        if (!$images) {
            return;
        }

        if (!is_array($images)) {
            $images = [$images];
        }

        if ($replaceOld) {
            $model->clearMediaCollection($collection);
        }

        foreach ($images as $image) {
            try {
                if (!$image instanceof UploadedFile || !$image->isValid()) {
                    Log::warning('Skipping invalid file: ' . ($image ? $image->getClientOriginalName() : 'N/A'));
                    continue;
                }

                $originalExtension = $image->getClientOriginalExtension();
                $safeExtension = empty($originalExtension) ? 'jpg' : strtolower($originalExtension);
                if ($safeExtension === 'jpeg') {
                    $safeExtension = 'jpg';
                }

                if ($image->getSize() > 2048 * 1024) {
                    $processedImage = Image::read($image)
                        ->resize(1200, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                    $tempPath = sys_get_temp_dir() . '/' . uniqid('resized_') . '.' . $safeExtension;
                    $processedImage->save($tempPath);

                    $model->addMedia($tempPath)
                        ->usingFileName($image->getClientOriginalName())
                        ->toMediaCollection($collection, $disk);

                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                } else {
                    $model->addMedia($image)
                        ->usingFileName($image->getClientOriginalName())
                        ->toMediaCollection($collection, $disk);
                }
            } catch (Exception $e) {
                Log::error('Image upload failed for ' . ($image->getClientOriginalName() ?? 'unknown file') . ': ' . $e->getMessage(), ['exception' => $e]);
            }
        }
    }
}
