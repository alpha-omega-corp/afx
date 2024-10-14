<?php

namespace App\Services;

use App\Interfaces\IFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService implements IFileService {

    public function store(UploadedFile $file): bool
    {
        $path = $file->store('public');
        return Storage::put($path, $file);
    }
}
