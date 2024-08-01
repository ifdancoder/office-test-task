<?php

namespace App\Services;

use App\Jobs\ProcessExcelFile;
use App\Models\Good;

use App\Helpers\DFileHelper;
use Illuminate\Support\Facades\Storage;
use App\Traits\FileGenTrait;

class GoodService
{
    use FileGenTrait;

    public function get($id)
    {
        $good = Good::where('id', $id);

        if ($good->exists()) {
            return $good->first();
        }

        return null;
    }

    public function getAll()
    {
        return Good::all();
    }
    public function createFromFile($data)
    {
        $file = $data['file'];

        $extension = $file->getClientOriginalExtension();
        
        $uniqueFilename = $this->getRandomFileName(Storage::path('public/files'), $extension);

        $filePath = $file->storeAs('public/files', $uniqueFilename . '.' . $extension);

        ProcessExcelFile::dispatch($filePath);
    }
}