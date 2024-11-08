<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\FileVersion\FileVersionService;

class FileVersionController extends Controller
{
    private FileVersionService $fileVersionService;

    // place the relations you want to return them within the response
    private array $relations = ['file'];

    public function __construct()
    {
        $this->fileVersionService = FileVersionService::make();
    }
}
