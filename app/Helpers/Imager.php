<?php

namespace App\Helpers;

use App\Enums\DiskDriver;
use App\Exceptions\DefaultException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Imager
{
    /**
     * Resize a image from storage.
     *
     * @param  string  $path
     * @param  \App\Enums\DiskDriver  $disk
     * @param  int  $width
     * @return \Intervention\Image\Image
     */
    public static function resize(string $path, DiskDriver $disk = DiskDriver::LOCAL, int $width = 0)
    {
        $url = explode('/', $path);
        $path = $url[count($url) - 1];

        $storageDisk = Storage::disk($disk->value);

        if (!$storageDisk->exists($path)) {
            throw new DefaultException(__('File :filename not found.', ['filename' => $path]));
        }

        $imageIns = Image::make($storageDisk->get($path));

        $aspectRatio = $imageIns->height() / $imageIns->width();

        if (0 == $width) {
            $width = $imageIns->width();
        }

        $height = $width * $aspectRatio;

        return $imageIns->resize($width, $height);
    }
}
