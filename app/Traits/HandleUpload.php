<?php

namespace App\Traits;

use App\Enums\DiskDriver;
use App\Exceptions\DefaultException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait HandleUpload
{
    /**
     * Load image from request and save it to storage.
     *
     * @param  string  $lastFile
     * @return array<string, string>
     */
    public function saveImage(UploadedFile $file, DiskDriver $disk = DiskDriver::LOCAL, string $lastFile = null, int $width = 0, int $height = 0)
    {
        if (null !== $lastFile) {
            $url = explode('/', $lastFile);
            $lastFile = $url[count($url) - 1];

            $exists = Storage::disk($disk->value)->exists($lastFile);

            if ($exists) {
                Storage::disk($disk->value)->delete($lastFile);
            }
        }

        if (0 == $width) {
            $width = Image::make($file)->width();
        }

        if (0 == $height) {
            $height = Image::make($file)->height();
        }

        $extension = $file->extension();

        $image = Image::make($file)->fit($width, $height)->encode($extension);

        $imageName = 'image_' . md5($image->__toString().rand()) . '.' . $extension;

        $imageSaved = Storage::disk($disk->value)->put($imageName, $image);

        if (!$imageSaved) {
            throw new DefaultException(__('An error occurred uploading the image.'));
        }

        $imageUrl = Storage::disk($disk->value)->url($imageName);

        if (!$imageUrl) {
            throw new DefaultException(__('File :filename not found', ['filename' => $imageName]));
        }

        return [
            'url' => $imageUrl,
            'name' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Load file/document from request and save it to storage.
     *
     * @return array<string, string>
     */
    public function saveFile(UploadedFile $file, DiskDriver $disk = DiskDriver::LOCAL)
    {
        $extension = $file->extension();

        $fileName = 'doc_' . md5($file->__toString().rand()) . '.' . $extension;

        $fileSaved = Storage::disk($disk->value)->put($fileName, $file);

        if (!$fileSaved) {
            throw new DefaultException(__('An error occurred uploading the file.'));
        }

        $fileUrl = Storage::disk($disk->value)->url($fileName);

        if (!$fileUrl) {
            throw new DefaultException(__('File :filename not found', ['filename' => $fileName]));
        }

        return [
            'url' => $fileUrl,
            'name' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Delete image/file from request and save it to storage.
     */
    public function deleteFile(string $file, DiskDriver $disk = DiskDriver::LOCAL): void
    {
        $url = explode('/', $file);
        $file = $url[count($url) - 1];

        $exists = Storage::disk($disk->value)->exists($file);

        if ($exists) {
            Storage::disk($disk->value)->delete($file);
        }
    }
}
