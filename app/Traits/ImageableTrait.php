<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use App\Traits\CurlTrait;

trait ImageableTrait
{
    use CurlTrait;

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function storeImageByUrl($url, $order)
    {
        $urlParts = parse_url($url);
        $filename = basename($urlParts['path']);

        $image = $this->images()->where('order', $order);

        if ($image->exists()) {
            $image = $image->first();
            $image->delete();
        }

        $image = new Image();
        $image->order = $order;
        $image->filename = $filename;
        $image->link = $url;
        $this->images()->save($image);
        
        $imageData = $this->file_get_contents_curl($url);

        if ($imageData == false) {
            $image->delete();
            return;
        }

        $imageInfo = getimagesizefromstring($imageData);

        if ($imageInfo !== false) {
            Storage::put($image->getPathToSave(), $imageData);
        } else {
            $image->delete();
            return;
        }

        chmod(Storage::path($image->getAllImagesFolder()), 0755);
        chgrp(Storage::path($image->getAllImagesFolder()), 'www-data');
        chmod(Storage::path($image->getFolder()), 0755);
        chgrp(Storage::path($image->getFolder()), 'www-data');
        chmod(Storage::path($image->getPathToSave()), 0644);
        chgrp(Storage::path($image->getPathToSave()), 'www-data');
    }

    public function imageByOrder($order)
    {
        return $this->images()->where('order', $order)->first();
    }

    public function deleteImageByOrder($order)
    {
        $image = $this->imageByOrder($order);
        $image->delete();
    }

    public function getFirstImage()
    {
        return $this->images()->where('order', 1)->first();
    }

    public function getFirstImageURL()
    {
        $img =$this->images()->where('order', 1);

        if ($img->exists()) {
            return $img->first()->getUrl();
        }

        return '/assets/dist/img/placeholder.jpg';
    }

    public function getOtherImages()
    {
        return $this->images()->where('order', '>', 1)->get();
    }
}