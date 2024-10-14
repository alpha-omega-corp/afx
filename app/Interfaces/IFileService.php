<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface IFileService
{
    public function store(UploadedFile $file): bool;
}
