<?php

namespace App\Services;

use App\Jobs\ProcessExcelFile;
use App\Models\Good;

class GoodService
{
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

        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('public/files', $fileName);

        ProcessExcelFile::dispatch($filePath)->withoutOverlapping();
    }
}