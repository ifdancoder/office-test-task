<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = ['link', 'filename', 'order', 'imageable_id', 'imageable_type'];
    
    public function getUrl()
    {
        return '/storage/images/' . $this->id . '/' . $this->filename;
    }

    public function getFolder()
    {
        return 'public/images/' . $this->id;
    }

    public function getAllImagesFolder()
    {
        return 'public/images/';
    }

    public function getPathToSave()
    {
        return 'public/images/' . $this->id . '/' . $this->filename;
    }

    public function delete()
    {
        Storage::deleteDirectory('public/images/' . $this->id);
        return parent::delete();
    }
}
