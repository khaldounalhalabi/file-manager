<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\StoreUpdateFileRequest;
use App\Services\v1\File\FileService;

class FileController extends Controller
{
    private FileService $fileService;

    // place the relations you want to return them within the response
    private array $relations = ['group', 'directory'];

    public function __construct()
    {
        $this->fileService = FileService::make();
    }

    public function store(StoreUpdateFileRequest $request)
    {
        $file = $this->fileService->store($request->validated());
        if ($file) {
            return redirect()->back()->with('success', __('site.stored_successfully'));
        }

        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }
}
