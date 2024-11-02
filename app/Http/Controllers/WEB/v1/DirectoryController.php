<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\Directory\DirectoryService;

class DirectoryController extends Controller
{
    private DirectoryService $directoryService;

    // place the relations you want to return them within the response
    private array $relations = ['group'];

    public function __construct()
    {
        $this->directoryService = DirectoryService::make();
    }
}
